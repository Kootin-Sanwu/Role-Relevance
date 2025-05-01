# Use official Python image
FROM python:3.11-slim

# Set working directory inside the container
WORKDIR /app

# Copy only the requirements first (for better Docker caching)
COPY requirements.txt .

# Install Python dependencies
RUN pip install --no-cache-dir -r requirements.txt

# Copy the entire ml-api directory into the container (excluding the .dockerignore files)
COPY . .

# Expose the port your Flask app runs on (different from 5000 if 5000 is already used!)
EXPOSE 5001

# Set environment variables (optional but recommended)
ENV FLASK_APP=app.py
ENV FLASK_RUN_HOST=0.0.0.0

# Run the Flask app
CMD ["python", "app.py"]
