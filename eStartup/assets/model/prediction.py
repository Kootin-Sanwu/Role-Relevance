# %% [code]
import torch
import numpy as np
import pandas as pd
# from google.colab import drive
from sklearn.ensemble import RandomForestRegressor
from sklearn.multioutput import MultiOutputRegressor
from sklearn.model_selection import train_test_split, GridSearchCV
from sklearn.metrics import mean_absolute_error, r2_score
from sklearn.feature_extraction.text import TfidfVectorizer
from sentence_transformers import SentenceTransformer, util
import tensorflow as tf
from tensorflow.keras.models import Sequential
from tensorflow.keras.layers import Dense, Dropout
from xgboost import XGBRegressor
from lightgbm import LGBMRegressor



# %% [code]
# Mount Google Drive and load the dataset
data_path = r"C:\xampp\htdocs\Local-Job-Role-Relevance\eStartup\assets\model\Dataset_1.csv"
df = pd.read_csv(data_path)


# Replace "Confidential" with NaN
df["Job Base Salary"] = df["Job Base Salary"].replace("Confidential", np.nan)
df["Job Base Salary"]

# Removing Salary Symbol

import re
import numpy as np

def extract_salary(salary_str):
    if pd.isna(salary_str) or salary_str == "Confidential":
        return np.nan  # Convert missing or "Confidential" values to NaN

    # Remove currency symbols and commas
    salary_str = re.sub(r'[^\d\-\s]', '', salary_str)  # Keep numbers and hyphen

    # Extract numbers
    numbers = re.findall(r'\d+', salary_str)
    numbers = list(map(int, numbers))  # Convert to integers

    if len(numbers) == 2:  # If it's a range, take the average
        return np.mean(numbers)
    elif len(numbers) == 1:  # If only one number exists
        return numbers[0]
    else:
        return np.nan  # If extraction fails

# Apply to Job Base Salary column
df["Job Base Salary"] = df["Job Base Salary"].apply(extract_salary)

# Fill NaN values with median salary
df["Job Base Salary"].fillna(df["Job Base Salary"].median(), inplace=True)

# Convert salary column to numeric (if formatted as text)
df["Job Base Salary"] = pd.to_numeric(df["Job Base Salary"], errors='coerce')
df["Job Base Salary"]

# Fill missing text columns with default values
df["Company Name"].fillna("No name provided", inplace=True)
df["Job Summary"].fillna("No summary provided", inplace=True)
df["Job Responsibilities"].fillna("No responsibilities specified", inplace=True)
df["Job Experience Requirements"].fillna("Not specified", inplace=True)

df["Job Summary"]

df["Job Responsibilities"]

df["Job Experience Requirements"]

# %% [code]
# Combine text fields into a single "Job Text" column
df["Job Text"] = df["Company Name"] + " " + df["Job Experience Requirements"] + " " + df["Job Responsibilities"] + " " + df["Job Summary"]
df["Job Text"] = df["Job Text"].str.lower()
df["Job Text"]


# %% [code]
# Load the SentenceTransformer model for BERT embeddings and similarity computations
bert_model = SentenceTransformer('all-MiniLM-L6-v2')



# Define metric descriptions for scoring
metric_texts = {
    "Revenue Generation": "increasing sales, boosting marketing, optimizing conversions, driving revenue",
    "Performance Improvement": "enhancing productivity, increasing efficiency, improving operations",
    "Cost Reduction": "reducing expenses, cutting costs, optimizing financial resources"
}



# Function to compute cosine similarity between job text and a given metric description
def compute_similarity(job_text, metric_text):
    job_emb = bert_model.encode(job_text, convert_to_tensor=True)
    metric_emb = bert_model.encode(metric_text, convert_to_tensor=True)
    return util.pytorch_cos_sim(job_emb, metric_emb).item()



# %% [code]
# Create TF-IDF features for "Job Text"
vectorizer = TfidfVectorizer(stop_words='english', max_features=100)
tfidf_matrix = vectorizer.fit_transform(df["Job Text"])
tfidf_df = pd.DataFrame(tfidf_matrix.toarray(), columns=vectorizer.get_feature_names_out())



# %% [code]
# Function to assign dynamic scores based on BERT similarity and TF-IDF keyword importance
def assign_dynamic_scores(job_text):
    scores = {}
    words = job_text.lower().split()

    # Find the row index corresponding to this job text
    idx = df.index[df["Job Text"] == job_text]
    idx = idx[0] if not idx.empty else 0

    # Sum TF-IDF scores for words present in the job text
    tfidf_score = sum(tfidf_df.at[idx, word] for word in words if word in tfidf_df.columns)

    # Calculate a final score for each metric
    for metric, metric_desc in metric_texts.items():
        similarity = compute_similarity(job_text, metric_desc)
        # Adjust weights as needed and clip the score between 10 and 100
        final_score = (similarity * 50) + (tfidf_score * 5)
        scores[metric] = min(100, max(10, final_score))

    return scores["Revenue Generation"], scores["Performance Improvement"], scores["Cost Reduction"]



# Apply the scoring function to all job texts
df["Revenue Generation"], df["Performance Improvement"], df["Cost Reduction"] = zip(*df["Job Text"].apply(assign_dynamic_scores))



# %% [code]
# Generate BERT embeddings for each job description
df["BERT Embedding"] = df["Job Text"].apply(lambda text: bert_model.encode(text))



# Convert embeddings into separate feature columns (384 dimensions for all-MiniLM-L6-v2)
X_bert = pd.DataFrame(df["BERT Embedding"].tolist(),
                      columns=[f"bert_feature_{i}" for i in range(384)])



# Use the TF-IDF features as a DataFrame
X_tfidf = tfidf_df.copy()



# Concatenate BERT and TF-IDF features
X = pd.concat([X_bert, X_tfidf], axis=1)


# Define target variables
y = df[["Revenue Generation", "Performance Improvement", "Cost Reduction"]]


# Split the dataset into training and test sets (80/20 split)
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)


# ----- Model 1: Random Forest Regression -----
rf_model = MultiOutputRegressor(RandomForestRegressor(n_estimators=100, random_state=42))
rf_model.fit(X_train, y_train)
y_pred_rf = rf_model.predict(X_test)



mae_rf = mean_absolute_error(y_test, y_pred_rf, multioutput='raw_values')
r2_rf = r2_score(y_test, y_pred_rf, multioutput='raw_values')
print("Random Forest Model Evaluation:")
print(f"MAE -> Revenue Gen: {mae_rf[0]:.2f}, Perf. Improve: {mae_rf[1]:.2f}, Cost Reduct: {mae_rf[2]:.2f}")
print(f"R²  -> Revenue Gen: {r2_rf[0]:.2f}, Perf. Improve: {r2_rf[1]:.2f}, Cost Reduct: {r2_rf[2]:.2f}")


# ----- Model 2: XGBoost Regression -----
xgb_model = MultiOutputRegressor(XGBRegressor(n_estimators=100, learning_rate=0.1, max_depth=6, random_state=42))
xgb_model.fit(X_train, y_train)
y_pred_xgb = xgb_model.predict(X_test)


mae_xgb = mean_absolute_error(y_test, y_pred_xgb, multioutput='raw_values')
r2_xgb = r2_score(y_test, y_pred_xgb, multioutput='raw_values')
print("\nXGBoost Model Evaluation:")
print(f"MAE -> Revenue Gen: {mae_xgb[0]:.2f}, Perf. Improve: {mae_xgb[1]:.2f}, Cost Reduct: {mae_xgb[2]:.2f}")
print(f"R²  -> Revenue Gen: {r2_xgb[0]:.2f}, Perf. Improve: {r2_xgb[1]:.2f}, Cost Reduct: {r2_xgb[2]:.2f}")


# ----- Model 3: Neural Network Regression -----
nn_model = Sequential([
    Dense(512, activation='relu', input_shape=(X_train.shape[1],)),
    Dropout(0.2),
    Dense(256, activation='relu'),
    Dropout(0.2),
    Dense(128, activation='relu'),
    Dense(3, activation='linear')  # Three output nodes for our metrics
])


nn_model.compile(optimizer='adam', loss='mse', metrics=['mae'])
nn_model.fit(X_train, y_train, epochs=50, batch_size=32, validation_split=0.2, verbose=1)
y_pred_nn = nn_model.predict(X_test)



mae_nn = mean_absolute_error(y_test, y_pred_nn, multioutput='raw_values')
r2_nn = r2_score(y_test, y_pred_nn, multioutput='raw_values')
print("\nNeural Network Model Evaluation:")
print(f"MAE -> Revenue Gen: {mae_nn[0]:.2f}, Perf. Improve: {mae_nn[1]:.2f}, Cost Reduct: {mae_nn[2]:.2f}")
print(f"R²  -> Revenue Gen: {r2_nn[0]:.2f}, Perf. Improve: {r2_nn[1]:.2f}, Cost Reduct: {r2_nn[2]:.2f}")


# ----- Model Comparison with Hyperparameter Tuning -----
# Define base models and corresponding hyperparameter grids
models = {
    "XGBoost": XGBRegressor(objective="reg:squarederror", random_state=42),
    "RandomForest": RandomForestRegressor(random_state=42),
    "LightGBM": LGBMRegressor(random_state=42)
}


param_grids = {
    "XGBoost": {
        "estimator__n_estimators": [100, 300, 500],
        "estimator__max_depth": [4, 6, 8],
        "estimator__learning_rate": [0.01, 0.1, 0.3]
    },
    "RandomForest": {
        "estimator__n_estimators": [100, 300, 500],
        "estimator__max_depth": [10, 20, None],
        "estimator__min_samples_split": [2, 5, 10]
    },
    "LightGBM": {
        "estimator__n_estimators": [100, 300, 500],
        "estimator__max_depth": [4, 6, 8],
        "estimator__learning_rate": [0.01, 0.1, 0.3]
    }
}


best_models = {}
for name, model in models.items():
    print(f"\nTuning {name}...")
    multi_model = MultiOutputRegressor(model)
    grid_search = GridSearchCV(multi_model, param_grids[name], cv=3,
                               scoring="neg_mean_absolute_error", verbose=2, n_jobs=-1)
    grid_search.fit(X_train, y_train)
    best_models[name] = grid_search.best_estimator_
    print(f"Best Parameters for {name}: {grid_search.best_params_}")

    # Evaluate on test data
    y_pred = best_models[name].predict(X_test)
    mae_val = mean_absolute_error(y_test, y_pred, multioutput="raw_values")
    r2_val = r2_score(y_test, y_pred, multioutput="raw_values")
    print(f"{name} Performance:")
    print(f"MAE -> Revenue Gen: {mae_val[0]:.2f}, Perf. Improve: {mae_val[1]:.2f}, Cost Reduct: {mae_val[2]:.2f}")
    print(f"R²  -> Revenue Gen: {r2_val[0]:.2f}, Perf. Improve: {r2_val[1]:.2f}, Cost Reduct: {r2_val[2]:.2f}")


# ----- Generating Predictions from the Best XGBoost Model -----
best_xgb = best_models["XGBoost"]
# predictions = best_xgb.predict(X_test)
predictions = best_xgb.predict(X)


# Create a DataFrame with predictions for all job titles
pred_df = pd.DataFrame(predictions, columns=["Revenue Generation", "Performance Improvement", "Cost Reduction"])
pred_df["Job Title"] = df["Job Title"]  # Use full dataset, not just test set
pred_df = pred_df[["Job Title", "Revenue Generation", "Performance Improvement", "Cost Reduction"]]

print("\nFull Dataset Predictions:")
pred_df

# Optionally, save the full dataset predictions
# pred_df.to_csv("/content/drive/MyDrive/Colab Notebooks/Web_Scraping/job_relevance_scores_full.csv", index=False)



import joblib

# Save the trained XGBoost model
model_path = "/content/drive/MyDrive/Colab Notebooks/Web_Scraping/best_xgb_model.pkl"
joblib.dump(best_xgb, model_path)

print(f"Model saved at: {model_path}")
