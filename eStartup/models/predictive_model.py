import pandas as pd
from joblib import load
import pickle

# Step 1: Load the saved models
kmeans = load('C:/Users/kooby/OneDrive - Ashesi University/Senior Year/Second Semester/Capstone/kmeans_model.joblib')  # Path to the KMeans model
scaler = load('C:/Users/kooby/OneDrive - Ashesi University/Senior Year/Second Semester/Capstone/scaler.joblib')        # Path to the scaler

# Step 2: Load the label encoders
with open('C:/Users/kooby/OneDrive - Ashesi University/Senior Year/Second Semester/Capstone/label_encoders.pkl', 'rb') as f:
    label_encoders = pickle.load(f)

# Step 3: Load a new dataset (or use your existing one)
new_data = pd.read_csv('C:/Users/kooby/OneDrive - Ashesi University/Senior Year/Second Semester/Capstone/processed_data.csv')

# Step 4: Preprocess the new data
# Encode categorical columns
columns_to_encode = list(label_encoders.keys())
for col in columns_to_encode:
    new_data[col] = label_encoders[col].transform(new_data[col])

# Scale numerical features
selected_features = ['Industry Demand Score', 'Availability', 'Average Salary', 'Growth Rate']
new_data[selected_features] = scaler.transform(new_data[selected_features])

# Step 5: Predict clusters with KMeans
new_data['Cluster'] = kmeans.predict(new_data[selected_features])

# Step 6: Perform scoring or further analysis
ranking_features = ['Industry Demand Score', 'Average Salary', 'Growth Rate']
new_data['Role Relevance Score'] = new_data[ranking_features].mean(axis=1)

# Display the results
print(new_data[['Role Name', 'Cluster', 'Role Relevance Score']])
