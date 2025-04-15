from flask import Flask, jsonify, request
import os
import joblib
import mysql.connector
import pandas as pd
import numpy as np
from sentence_transformers import SentenceTransformer
from flask_cors import CORS  # ✅ Import CORS

# Load trained model
MODEL_PATH = r"/var/www/html/Role-Relevance/eStartup/assets/models/final_job_role_model.pkl"
VECTORIZER_PATH = r"/var/www/html/Role-Relevance/eStartup/assets/models/tfidf_vectorizer.pkl"
BERT_CONFIG_PATH = r"/var/www/html/Role-Relevance/eStartup/assets/models/bert_config.pkl"
METRIC_PATH = r"/var/www/html/Role-Relevance/eStartup/assets/models/metric_info.pkl"

# Check if model files exist
if not os.path.exists(MODEL_PATH):
    raise FileNotFoundError(f"Model file not found at {MODEL_PATH}")
if not os.path.exists(VECTORIZER_PATH):
    raise FileNotFoundError(f"TfidfVectorizer file not found at {VECTORIZER_PATH}")
if not os.path.exists(BERT_CONFIG_PATH):
    raise FileNotFoundError(f"BERT config file not found at {BERT_CONFIG_PATH}")
if not os.path.exists(METRIC_PATH):
    raise FileNotFoundError(f"Metric info file not found at {METRIC_PATH}")

# Load the model and related files
model = joblib.load(MODEL_PATH)
vectorizer = joblib.load(VECTORIZER_PATH)
bert_config = joblib.load(BERT_CONFIG_PATH)
metric_info = joblib.load(METRIC_PATH)

# Load the BERT model based on the saved configuration
bert_model_name = bert_config.get('model_name', 'all-mpnet-base-v2')
embedding_dim = bert_config.get('embedding_dim', 768)  # Default for all-mpnet-base-v2
bert_model = SentenceTransformer(bert_model_name)

# Define all six metrics in order
metrics_order = [
    "Revenue Generation", 
    "Performance Improvement", 
    "Cost Reduction", 
    "Market Demand", 
    "Technological Susceptibility", 
    "Interdepartmental Dependence"
]

app = Flask(__name__)








def get_organization_roles(org_id):
    conn = mysql.connector.connect(
        host="127.0.0.1",
        user="root",
        password="K22.Kb16.Nk28.Ny27",
        database="RoleEvaluation"
    )
    cursor = conn.cursor()

    cursor.execute("SELECT JobRoleID, RoleName, RoleDescription FROM JobRoles WHERE OrganizationID = %s", (org_id,))

    job_roles = cursor.fetchall()

    conn.close()

    if not job_roles:
        print(f"Warning: No job roles found for Organization ID {org_id}")
    
    return job_roles








def get_organization_description(org_id):
    conn = mysql.connector.connect(
        host="127.0.0.1",
        user="root",
        password="K22.Kb16.Nk28.Ny27",
        database="RoleEvaluation"
    )
    cursor = conn.cursor()

    # ✅ Correct query to get the organization's description
    cursor.execute("SELECT Description FROM Organizations WHERE OrganizationID = %s", (org_id,))
    org_description = cursor.fetchone()

    conn.close()

    if org_description and org_description[0]:
        return org_description[0]
    else:
        print(f"Warning: No description found for Organization ID {org_id}")
        return ""








def get_predictions(job_roles, org_description):
    """
    Generate predictions for job roles using the trained model.
    
    Args:
        job_roles: List of tuples containing (id, title, description)
        org_description: Organization description as context
        
    Returns:
        Dictionary of predictions keyed by job role ID and title
    """
    if job_roles is None or len(job_roles) == 0:
        print("Error: job_roles is empty or None.")
        return {}

    try:
        # Extract job titles and descriptions
        job_ids = [role[0] for role in job_roles]
        job_titles = [role[1] for role in job_roles]
        job_descriptions = [role[2] if role[2] is not None else "" for role in job_roles]

        # Combine organization description with each job description
        combined_descriptions = [
            f"{org_description.strip()} {desc.strip()}" if desc.strip() else org_description.strip()
            for desc in job_descriptions
        ]

        # Generate BERT embeddings
        print(f"Generating BERT embeddings using model: {bert_model_name}")
        bert_embeddings = []
        for text in combined_descriptions:
            if not text.strip():
                # Handle empty text with zeros
                embedding = np.zeros(embedding_dim)
            else:
                # Generate embedding for the text
                embedding = bert_model.encode(text)
            bert_embeddings.append(embedding)
        
        bert_embeddings = np.array(bert_embeddings)
        print(f"BERT embeddings shape: {bert_embeddings.shape}")
        
        # Create dataframe from BERT embeddings
        X_bert = pd.DataFrame(
            bert_embeddings,
            columns=[f"bert_feature_{i}" for i in range(embedding_dim)]
        )
        
        # Generate TF-IDF features for the combined descriptions
        print("Generating TF-IDF features")
        tfidf_matrix = vectorizer.transform(combined_descriptions)
        X_tfidf = pd.DataFrame(
            tfidf_matrix.toarray(),
            columns=vectorizer.get_feature_names_out()
        )
        print(f"TF-IDF features shape: {X_tfidf.shape}")
        
        # Combine features
        X = pd.concat([X_bert, X_tfidf], axis=1)
        
        # Ensure the features match the model's expected input
        expected_features = getattr(model, 'feature_names_in_', None)
        if expected_features is not None:
            # For scikit-learn models that store feature names
            missing_cols = set(expected_features) - set(X.columns)
            extra_cols = set(X.columns) - set(expected_features)
            
            # Add missing columns with zeros
            for col in missing_cols:
                X[col] = 0
                
            # Keep only the columns the model expects
            X = X[expected_features]
            
            print(f"Adjusted feature count to match model: {X.shape[1]}")
        
        # Make predictions
        print("Making predictions...")
        predictions = model.predict(X)
        print(f"Predictions shape: {predictions.shape}")
        
        # Create the results dictionary
        results = {}
        for i in range(len(job_roles)):
            # Add all six metrics to the results
            role_metrics = {}
            for j, metric_name in enumerate(metrics_order):
                role_metrics[metric_name] = float(predictions[i][j])
            
            # Store results with job ID and title as key
            results[(job_ids[i], job_titles[i])] = role_metrics
            print(results)
        
        return results
    
    except Exception as e:
        print(f"Error in get_predictions: {str(e)}")
        import traceback
        traceback.print_exc()
        return {}








def update_role_scores(org_id, predictions):
    """Update role relevance scores in the database."""
    conn = mysql.connector.connect(
        host="127.0.0.1",
        user="root",
        password="K22.Kb16.Nk28.Ny27",
        database="RoleEvaluation"
    )
    cursor = conn.cursor()
    
    for (job_role_id, _), scores in predictions.items():
        cursor.execute("""
            UPDATE JobRoles 
            SET RevenueGenerationScore = %s, PerformanceImprovementScore = %s, CostReductionScore = %s, MarketDemandScore = %s, TechnologicalSusceptibilityScore = %s, InterdepartmentalDependenceScore = %s
            WHERE JobRoleID = %s
        """, (scores["Revenue Generation"], scores["Performance Improvement"], scores["Cost Reduction"], scores["Market Demand"], scores["Technological Susceptibility"], scores["Interdepartmental Dependence"], job_role_id))
    
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
def get_performance_improvement_scores():
    
    # Print all headers for debugging
    print("Request Headers:", dict(request.headers)) 
    
    """API endpoint to get revenue generation scores."""
    org_id = get_logged_in_org_id()
    if not org_id:
        return jsonify({"error": "Organization ID is missing"}), 401

    job_roles = get_organization_roles(org_id)
    if not job_roles:
        return jsonify({"error": "No roles found for the organization"}), 404
    
    org_description = get_organization_description(org_id)
    if not job_roles:
        return jsonify({"error": "No roles found for the organization"}), 404

    predictions = get_predictions(job_roles, org_description)
    update_role_scores(org_id, predictions)

    formatted_predictions = [
        {
            "Role Name": role_name.replace(" ", "\n"),  # Format role name
            "Performance Improvement Score": round(scores["Performance Improvement"], 2)
        }
        for (_, role_name), scores in predictions.items()
    ]

    return jsonify(formatted_predictions)








@app.route('/predict_2', methods=['POST'])
def get_cost_reduction_scores():
    """API endpoint to get performance improvement scores."""
    org_id = get_logged_in_org_id()
    if not org_id:
        return jsonify({"error": "Organization ID is missing"}), 401

    job_roles = get_organization_roles(org_id)
    if not job_roles:
        return jsonify({"error": "No roles found for the organization"}), 404
    
    org_description = get_organization_description(org_id)
    if not job_roles:
        return jsonify({"error": "No roles found for the organization"}), 404

    predictions = get_predictions(job_roles, org_description)
    update_role_scores(org_id, predictions)

    formatted_predictions = [
        {
            "Role Name": role_name.replace(" ", "\n"),
            "Cost Reduction Score": round(scores["Cost Reduction"], 2)
        }
        for (_, role_name), scores in predictions.items()
    ]

    return jsonify(formatted_predictions)








@app.route('/predict_3', methods=['POST'])
def get_revenue_generation_scores():
    """API endpoint to get cost reduction scores."""
    org_id = get_logged_in_org_id()
    if not org_id:
        return jsonify({"error": "Organization ID is missing"}), 401

    job_roles = get_organization_roles(org_id)
    if not job_roles:
        return jsonify({"error": "No roles found for the organization"}), 404
    
    org_description = get_organization_description(org_id)
    if not job_roles:
        return jsonify({"error": "No roles found for the organization"}), 404

    predictions = get_predictions(job_roles, org_description)
    update_role_scores(org_id, predictions)

    formatted_predictions = [
        {
            "Role Name": role_name.replace(" ", "\n"),
            "Revenue Generation Score": round(scores["Revenue Generation"], 2)
        }
        for (_, role_name), scores in predictions.items()
    ]

    return jsonify(formatted_predictions)








@app.route('/predict_4', methods=['POST'])
def get_market_demand_scores():
    """API endpoint to get cost reduction scores."""
    org_id = get_logged_in_org_id()
    if not org_id:
        return jsonify({"error": "Organization ID is missing"}), 401

    job_roles = get_organization_roles(org_id)
    if not job_roles:
        return jsonify({"error": "No roles found for the organization"}), 404
    
    org_description = get_organization_description(org_id)
    if not job_roles:
        return jsonify({"error": "No roles found for the organization"}), 404

    predictions = get_predictions(job_roles, org_description)
    update_role_scores(org_id, predictions)

    formatted_predictions = [
        {
            "Role Name": role_name.replace(" ", "\n"),                                        
            "Market Demand Score": round(scores["Market Demand"], 2)
        }
        for (_, role_name), scores in predictions.items()
    ]

    return jsonify(formatted_predictions)








@app.route('/predict_5', methods=['POST'])
def get_tech_sus_scores():
    """API endpoint to get cost reduction scores."""
    org_id = get_logged_in_org_id()
    if not org_id:
        return jsonify({"error": "Organization ID is missing"}), 401

    job_roles = get_organization_roles(org_id)
    if not job_roles:
        return jsonify({"error": "No roles found for the organization"}), 404
    
    org_description = get_organization_description(org_id)
    if not job_roles:
        return jsonify({"error": "No roles found for the organization"}), 404

    predictions = get_predictions(job_roles, org_description)
    update_role_scores(org_id, predictions)

    formatted_predictions = [
        {
            "Role Name": role_name.replace(" ", "\n"),
            "Technological Susceptibility Score": round(scores["Technological Susceptibility"], 2)
        }
        for (_, role_name), scores in predictions.items()
    ]

    return jsonify(formatted_predictions)








@app.route('/predict_6', methods=['POST'])
def get_inter_depend_scores():
    """API endpoint to get cost reduction scores."""
    org_id = get_logged_in_org_id()
    if not org_id:
        return jsonify({"error": "Organization ID is missing"}), 401

    job_roles = get_organization_roles(org_id)
    if not job_roles:
        return jsonify({"error": "No roles found for the organization"}), 404
    
    org_description = get_organization_description(org_id)
    if not job_roles:
        return jsonify({"error": "No roles found for the organization"}), 404

    predictions = get_predictions(job_roles, org_description)
    update_role_scores(org_id, predictions)

    formatted_predictions = [
        {
            "Role Name": role_name.replace(" ", "\n"),
            "Interdepartmental Dependence Score": round(scores["Interdepartmental Dependence"], 2)
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

    job_roles = get_organization_roles(org_id)
    if not job_roles:
        return jsonify({"error": "No roles found for the organization"}), 404

    org_description = get_organization_description(org_id)
    if not job_roles:
        return jsonify({"error": "No roles found for the organization"}), 404

    predictions = get_predictions(job_roles, org_description)
    update_role_scores(org_id, predictions)
    
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








@app.route('/get_scores_2', methods=['POST'])
def get_role_scores_2():
    """Fetch role relevance scores directly from the database."""
    org_id = get_logged_in_org_id()
    if not org_id:
        return jsonify({"error": "Organization ID is missing"}), 401

    job_roles = get_organization_roles(org_id)
    if not job_roles:
        return jsonify({"error": "No roles found for the organization"}), 404

    org_description = get_organization_description(org_id)
    if not job_roles:
        return jsonify({"error": "No roles found for the organization"}), 404

    predictions = get_predictions(job_roles, org_description)
    update_role_scores(org_id, predictions)

    conn = mysql.connector.connect(
        host="127.0.0.1",
        user="root",
        password="K22.Kb16.Nk28.Ny27",
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








@app.route('/get_scores_3', methods=['POST'])
def get_role_scores_3():
    """Fetch role relevance scores directly from the database."""
    org_id = get_logged_in_org_id()
    if not org_id:
        return jsonify({"error": "Organization ID is missing"}), 401

    job_roles = get_organization_roles(org_id)
    if not job_roles:
        return jsonify({"error": "No roles found for the organization"}), 404

    org_description = get_organization_description(org_id)
    if not job_roles:
        return jsonify({"error": "No roles found for the organization"}), 404

    predictions = get_predictions(job_roles, org_description)
    update_role_scores(org_id, predictions)

    conn = mysql.connector.connect(
        host="127.0.0.1",
        user="root",
        password="K22.Kb16.Nk28.Ny27",
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








@app.route('/get_scores_4', methods=['POST'])
def get_role_scores_4():
    """Fetch role relevance scores directly from the database."""
    org_id = get_logged_in_org_id()
    if not org_id:
        return jsonify({"error": "Organization ID is missing"}), 401

    job_roles = get_organization_roles(org_id)
    if not job_roles:
        return jsonify({"error": "No roles found for the organization"}), 404

    org_description = get_organization_description(org_id)
    if not job_roles:
        return jsonify({"error": "No roles found for the organization"}), 404

    predictions = get_predictions(job_roles, org_description)
    update_role_scores(org_id, predictions)

    conn = mysql.connector.connect(
        host="127.0.0.1",
        user="root",
        password="K22.Kb16.Nk28.Ny27",
        database="RoleEvaluation"
    )
    cursor = conn.cursor(dictionary=True)

    cursor.execute("""
        SELECT RoleName AS role_name, MarketDemandScore AS relevance_score
        FROM JobRoles WHERE OrganizationID = %s
    """, (org_id,))

    role_scores = cursor.fetchall()
    conn.close()

    if not role_scores:
        return jsonify({"error": "No scores found for the organization"}), 404

    return jsonify(role_scores)








@app.route('/get_scores_5', methods=['POST'])
def get_role_scores_5():
    """Fetch role relevance scores directly from the database."""
    org_id = get_logged_in_org_id()
    if not org_id:
        return jsonify({"error": "Organization ID is missing"}), 401

    job_roles = get_organization_roles(org_id)
    if not job_roles:
        return jsonify({"error": "No roles found for the organization"}), 404

    org_description = get_organization_description(org_id)
    if not job_roles:
        return jsonify({"error": "No roles found for the organization"}), 404

    predictions = get_predictions(job_roles, org_description)
    update_role_scores(org_id, predictions)

    conn = mysql.connector.connect(
        host="127.0.0.1",
        user="root",
        password="K22.Kb16.Nk28.Ny27",
        database="RoleEvaluation"
    )
    cursor = conn.cursor(dictionary=True)

    cursor.execute("""
        SELECT RoleName AS role_name, TechnologicalSusceptibilityScore AS relevance_score
        FROM JobRoles WHERE OrganizationID = %s
    """, (org_id,))

    role_scores = cursor.fetchall()
    conn.close()

    if not role_scores:
        return jsonify({"error": "No scores found for the organization"}), 404

    return jsonify(role_scores)








@app.route('/get_scores_6', methods=['POST'])
def get_role_scores_6():
    """Fetch role relevance scores directly from the database."""
    org_id = get_logged_in_org_id()
    if not org_id:
        return jsonify({"error": "Organization ID is missing"}), 401

    job_roles = get_organization_roles(org_id)
    if not job_roles:
        return jsonify({"error": "No roles found for the organization"}), 404

    org_description = get_organization_description(org_id)
    if not job_roles:
        return jsonify({"error": "No roles found for the organization"}), 404

    predictions = get_predictions(job_roles, org_description)
    update_role_scores(org_id, predictions)

    conn = mysql.connector.connect(
        host="127.0.0.1",
        user="root",
        password="K22.Kb16.Nk28.Ny27",
        database="RoleEvaluation"
    )
    cursor = conn.cursor(dictionary=True)

    cursor.execute("""
        SELECT RoleName AS role_name, InterdepartmentalDependenceScore AS relevance_score
        FROM JobRoles WHERE OrganizationID = %s
    """, (org_id,))

    role_scores = cursor.fetchall()
    conn.close()

    if not role_scores:
        return jsonify({"error": "No scores found for the organization"}), 404

    return jsonify(role_scores)

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000, debug=True)