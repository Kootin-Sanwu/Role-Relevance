
def calculate_role_relevance(data, ranking_features):
    data['Role Relevance Score'] = data[ranking_features].mean(axis=1)
    data['Role Relevance Score'] = (data['Role Relevance Score'] - data['Role Relevance Score'].min()) /                                     (data['Role Relevance Score'].max() - data['Role Relevance Score'].min()) * 100
    return data
