from flask import Flask, jsonify, request
import os
import joblib
import mysql.connector
import pandas as pd
import numpy as np
from sentence_transformers import SentenceTransformer
from flask_cors import CORS  # ✅ Import CORS


# Load trained model
MODEL_PATH = r"C:\xampp\htdocs\Local-Job-Role-Relevance-3\eStartup\assets\model\best_xgb_model.pkl"
VECTORIZER_PATH = r"C:\xampp\htdocs\Local-Job-Role-Relevance-3\eStartup\assets\model\tfidf_vectorizer.pkl"

if not os.path.exists(MODEL_PATH):
    raise FileNotFoundError(f"Model file not found at {MODEL_PATH}")
if not os.path.exists(VECTORIZER_PATH):
    raise FileNotFoundError(f"TfidfVectorizer file not found at {VECTORIZER_PATH}")

model = joblib.load(MODEL_PATH)
vectorizer = joblib.load(VECTORIZER_PATH)
bert_model = SentenceTransformer("sentence-transformers/all-MiniLM-L6-v2")  # Load BERT model

app = Flask(__name__)

def get_organization_roles(org_id):
    conn = mysql.connector.connect(
        host="localhost",
        user="root",
        password="",
        database="RoleEvaluation"
    )
    cursor = conn.cursor()

    cursor.execute("SELECT JobRoleID, RoleName, RoleDescription FROM JobRoles WHERE OrganizationID = %s", (org_id,))

    job_roles = cursor.fetchall()

    conn.close()
    
    if not job_roles:
        print(f"Warning: No job roles found for Organization ID {org_id}")
    
    return job_roles


def get_predictions(job_roles):
    if job_roles is None or len(job_roles) == 0:
        print("Error: job_roles is empty or None.")
        return {}

    job_titles = [role[1] for role in job_roles]  # Extract role names
    job_descriptions = [role[2] if role[2] is not None else "" for role in job_roles]  # Handle missing descriptions

    print(job_descriptions)

    # Compute BERT embeddings (use zero-vector for missing descriptions)
    bert_embeddings = np.array([
        bert_model.encode(desc) if desc.strip() else np.zeros(384)
        for desc in job_descriptions
    ])

    # Ensure BERT shape is correct
    if bert_embeddings.shape[1] != 384:
        raise ValueError(f"Unexpected BERT embedding shape: {bert_embeddings.shape}")

    # Convert BERT embeddings to DataFrame
    X_bert = pd.DataFrame(bert_embeddings, columns=[f"bert_feature_{i}" for i in range(384)])

    # Transform job titles using the same vectorizer used in training
    job_roles_vectorized = vectorizer.transform(job_titles).toarray()

    # Convert to DataFrame using correct feature names
    X_tfidf = pd.DataFrame(job_roles_vectorized, columns=vectorizer.get_feature_names_out())

    # Reindex DataFrame to match expected feature names (ensures alignment with training)
    expected_tfidf_features = model.feature_names_in_[384:]  # Last 100 features should be TF-IDF features
    X_tfidf = X_tfidf.reindex(columns=expected_tfidf_features, fill_value=0)

    # Ensure TF-IDF shape is correct
    if X_tfidf.shape[1] != 100:
        raise ValueError(f"Unexpected TF-IDF shape: {X_tfidf.shape}")

    # Concatenate BERT and TF-IDF features
    X = pd.concat([X_bert, X_tfidf], axis=1)
    
    # Ensure feature order consistency
    X = X.reindex(columns=model.feature_names_in_, fill_value=0)

    # Ensure final shape before passing to model
    if X.shape[1] != 484:
        raise ValueError(f"Feature shape mismatch: Expected 484, but got {X.shape[1]}")

    # Predict relevance scores
    predictions = model.predict(X)

    # Return all three scores for each job role
    return {
        (job_roles[i][0], job_titles[i]): {
            "Revenue Generation": float(predictions[i][0]),
            "Performance Improvement": float(predictions[i][1]),
            "Cost Reduction": float(predictions[i][2]),
        }
        for i in range(len(job_roles))
    }


def update_role_scores(org_id, predictions):
    """Update role relevance scores in the database."""
    conn = mysql.connector.connect(
        host="localhost",
        user="root",
        password="",
        database="RoleEvaluation"
    )
    cursor = conn.cursor()
    
    for (job_role_id, _), scores in predictions.items():
        cursor.execute("""
            UPDATE JobRoles 
            SET RevenueGenerationScore = %s, PerformanceImprovementScore = %s, CostReductionScore = %s
            WHERE JobRoleID = %s
        """, (scores["Revenue Generation"], scores["Performance Improvement"], scores["Cost Reduction"], job_role_id))
    
    conn.commit()
    conn.close()


def get_logged_in_org_id():
    org_id = request.headers.get("Organizationid")  # Read org_id from headers
    print(f"Extracted Organization ID (Raw): {org_id}, Type: {type(org_id)}")  # Debugging line
    if not org_id:
        print("Error: Organization ID is missing from headers")
        return None
    
        # Ensure it's an integer
    try:
        org_id = int(org_id)  # Convert to integer
        print(f"Converted Organization ID: {org_id}, Type: {type(org_id)}")  # Debugging line
    except ValueError:
        print("Error: Organization ID is not a valid integer")
        return None

    return org_id

from flask import Flask, request, jsonify
from flask_cors import CORS  # ✅ Import CORS

app = Flask(__name__)
CORS(app, resources={r"/*": {"origins": "*"}})  # ✅ Allow all origins

@app.route('/predict_1', methods=['POST'])
def get_revenue_generation_scores():
    
    # Print all headers for debugging
    print("Request Headers:", dict(request.headers)) 
    
    """API endpoint to get revenue generation scores."""
    org_id = get_logged_in_org_id()
    if not org_id:
        return jsonify({"error": "Organization ID is missing"}), 401

    job_roles = get_organization_roles(org_id)
    if not job_roles:
        return jsonify({"error": "No roles found for the organization"}), 404

    predictions = get_predictions(job_roles)
    update_role_scores(org_id, predictions)

    formatted_predictions = [
        {
            "Role Name": role_name.replace(" ", "\n"),  # Format role name
            "Revenue Generation Score": round(scores["Revenue Generation"], 2)
        }
        for (_, role_name), scores in predictions.items()
    ]

    return jsonify(formatted_predictions)


@app.route('/predict_2', methods=['POST'])
def get_performance_improvement_scores():
    """API endpoint to get performance improvement scores."""
    org_id = get_logged_in_org_id()
    if not org_id:
        return jsonify({"error": "Organization ID is missing"}), 401

    job_roles = get_organization_roles(org_id)
    if not job_roles:
        return jsonify({"error": "No roles found for the organization"}), 404

    predictions = get_predictions(job_roles)
    update_role_scores(org_id, predictions)

    formatted_predictions = [
        {
            "Role Name": role_name.replace(" ", "\n"),
            "Performance Improvement Score": round(scores["Performance Improvement"], 2)
        }
        for (_, role_name), scores in predictions.items()
    ]

    return jsonify(formatted_predictions)


@app.route('/predict_3', methods=['POST'])
def get_cost_reduction_scores():
    """API endpoint to get cost reduction scores."""
    org_id = get_logged_in_org_id()
    if not org_id:
        return jsonify({"error": "Organization ID is missing"}), 401

    job_roles = get_organization_roles(org_id)
    if not job_roles:
        return jsonify({"error": "No roles found for the organization"}), 404

    predictions = get_predictions(job_roles)
    update_role_scores(org_id, predictions)

    formatted_predictions = [
        {
            "Role Name": role_name.replace(" ", "\n"),
            "Cost Reduction Score": round(scores["Cost Reduction"], 2)
        }
        for (_, role_name), scores in predictions.items()
    ]

    return jsonify(formatted_predictions)


@app.route('/get_scores_1', methods=['POST'])
def get_role_scores_1():
    
    # Print all headers for debugging
    print("Request Headers:", dict(request.headers)) 
    
    """Fetch role relevance scores directly from the database."""
    org_id = get_logged_in_org_id()
    if not org_id:
        return jsonify({"error": "Organization ID is missing"}), 401

    conn = mysql.connector.connect(
        host="localhost",
        user="root",
        password="",
        database="RoleEvaluation"
    )
    cursor = conn.cursor(dictionary=True)

    cursor.execute("""
        SELECT RoleName AS role_name, RevenueGenerationScore AS relevance_score
        FROM JobRoles WHERE OrganizationID = %s
    """, (org_id,))

    role_scores = cursor.fetchall()
    conn.close()

    if not role_scores:
        return jsonify({"error": "No scores found for the organization"}), 404

    return jsonify(role_scores)

@app.route('/get_scores_2', methods=['POST'])
def get_role_scores_2():
    """Fetch role relevance scores directly from the database."""
    org_id = get_logged_in_org_id()
    if not org_id:
        return jsonify({"error": "Organization ID is missing"}), 401

    conn = mysql.connector.connect(
        host="127.0.0.1",
        user="root",
        password="K22.Kb16.Nk28.Ny27",
        database="RoleEvaluation"
    )
    cursor = conn.cursor(dictionary=True)

    cursor.execute("""
        SELECT RoleName AS role_name, PerformanceImprovementScore AS relevance_score
        FROM JobRoles WHERE OrganizationID = %s
    """, (org_id,))

    role_scores = cursor.fetchall()
    conn.close()

    if not role_scores:
        return jsonify({"error": "No scores found for the organization"}), 404

    return jsonify(role_scores)


@app.route('/get_scores_3', methods=['POST'])
def get_role_scores_3():
    """Fetch role relevance scores directly from the database."""
    org_id = get_logged_in_org_id()
    if not org_id:
        return jsonify({"error": "Organization ID is missing"}), 401

    conn = mysql.connector.connect(
        host="localhost",
        user="root",
        password="",
        database="RoleEvaluation"
    )
    cursor = conn.cursor(dictionary=True)

    cursor.execute("""
        SELECT RoleName AS role_name, CostReductionScore AS relevance_score
        FROM JobRoles WHERE OrganizationID = %s
    """, (org_id,))

    role_scores = cursor.fetchall()
    conn.close()

    if not role_scores:
        return jsonify({"error": "No scores found for the organization"}), 404

    return jsonify(role_scores)

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000, debug=True)