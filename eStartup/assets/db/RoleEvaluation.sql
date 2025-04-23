-- MySQL Database Schema for the Job Role Relevance Evaluation System
DROP DATABASE IF EXISTS RoleEvaluation;

CREATE DATABASE RoleEvaluation;

USE RoleEvaluation;

-- Table to store organizational information
CREATE TABLE Organizations (
    OrganizationID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(255) NOT NULL,
    Email VARCHAR(255) UNIQUE NOT NULL,
    Password VARCHAR(255) NOT NULL,
    Description TEXT,  -- New column to store the description
    RegistrationDate DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Table to store user information (for organizational users)
CREATE TABLE Users (
    UserID INT AUTO_INCREMENT PRIMARY KEY,
    OrganizationID INT,
    Name VARCHAR(255) NOT NULL,
    Email VARCHAR(255) UNIQUE NOT NULL,
    Password VARCHAR(255) NOT NULL,
    Role VARCHAR(255),
    RegistrationDate DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (OrganizationID) REFERENCES Organizations(OrganizationID)
);

-- Table to store job roles and their scores
CREATE TABLE JobRoles (
    JobRoleID INT AUTO_INCREMENT PRIMARY KEY,
    OrganizationID INT,
    RoleName VARCHAR(255) NOT NULL,
    RoleDescription TEXT,
    CurrentScore DECIMAL(5, 2),
    RevenueGenerationScore DECIMAL(5, 2),
    PerformanceImprovementScore DECIMAL(5, 2),
    CostReductionScore DECIMAL(5, 2),
    MarketDemandScore DECIMAL(5, 2),
    TechnologicalSusceptibilityScore DECIMAL(5, 2),
    InterdepartmentalDependenceScore DECIMAL(5, 2),
    LastUpdated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (OrganizationID) REFERENCES Organizations(OrganizationID)
);

-- Table to store historical score data for job roles
CREATE TABLE JobRoleScores (
    ScoreID INT AUTO_INCREMENT PRIMARY KEY,
    JobRoleID INT,
    Score DECIMAL(5, 2) NOT NULL,
    EvaluationDate DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (JobRoleID) REFERENCES JobRoles(JobRoleID)
);

-- Table to store visualization data (e.g., graphs)
CREATE TABLE Visualizations (
    VisualizationID INT AUTO_INCREMENT PRIMARY KEY,
    OrganizationID INT,
    GraphType VARCHAR(255) NOT NULL,
    Data TEXT NOT NULL,
    CreatedAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (OrganizationID) REFERENCES Organizations(OrganizationID)
);

-- Table to log user activity (e.g., downloads, logins)
CREATE TABLE ActivityLog (
    LogID INT AUTO_INCREMENT PRIMARY KEY,
    UserID INT,
    ActivityType VARCHAR(255) NOT NULL,
    ActivityDetails TEXT,
    ActivityTimestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (UserID) REFERENCES Users(UserID)
);

-- Table to manage downloadable files
CREATE TABLE Downloads (
    DownloadID INT AUTO_INCREMENT PRIMARY KEY,
    OrganizationID INT,
    FileName VARCHAR(255) NOT NULL,
    FilePath VARCHAR(255) NOT NULL,
    DownloadDate DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (OrganizationID) REFERENCES Organizations(OrganizationID)
);

-- Create a temporary table to hold the imported data with combined description
CREATE TABLE temp_job_roles (
    id INT PRIMARY KEY,
    role_title VARCHAR(255) NOT NULL,
    combined_description TEXT
);

-- Insert the data into temporary table with company, industry and description combined
INSERT INTO temp_job_roles (id, role_title, combined_description) VALUES
(0, 'Software Engineer', 'Designs, develops, and maintains software systems and applications to meet specific requirements. Writes, tests, and debugs code using various programming languages and frameworks. Collaborates with cross-functional teams to understand requirements and translate them into technical solutions. Implements best practices for software design, security, and performance optimization. Participates in code reviews, troubleshoots issues, and documents processes. Stays current with emerging technologies and continuously improves technical skills to deliver innovative software solutions.'),
(1, 'Data Scientist', 'Analyzes complex datasets to extract actionable insights and solve business problems. Applies statistical methods, machine learning algorithms, and data visualization techniques to identify patterns and trends. Builds predictive models and conducts experiments to test hypotheses. Collaborates with stakeholders to understand business needs and communicate findings effectively. Develops data pipelines and implements automated solutions using programming languages such as Python or R. Stays abreast of advancements in data science methodologies and tools to drive data-informed decision making.'),
(2, 'UX/UI Designer', 'Responsible for creating intuitive, engaging user experiences and interfaces for digital products. Conducts user research, develops wireframes and prototypes, and collaborates with development teams to implement designs. Applies principles of visual design, information architecture, and user psychology to create accessible, aesthetically pleasing interfaces that meet both user needs and business goals. Maintains consistency across platforms while keeping up with current design trends and accessibility standards.'),
(3, 'Registered Nurse', 'Provides direct patient care in healthcare settings, developing and implementing nursing care plans. Assesses patient conditions, administers medications and treatments, and monitors vital signs. Documents patient information and communicates with healthcare team members about patient status. Educates patients and families about health conditions and post-treatment care. Follows infection control protocols and maintains a safe environment for patients. Collaborates with physicians and other healthcare professionals to coordinate comprehensive care while advocating for patients needs and rights.'),
(4, 'Marketing Manager', 'Develops and implements marketing strategies to promote products or services and drive business growth. Creates marketing campaigns across multiple channels, including digital, social media, and traditional platforms. Analyzes market trends and consumer behavior to identify opportunities and optimize marketing efforts. Manages budgets, sets performance metrics, and tracks campaign results. Coordinates with sales, product development, and creative teams to ensure consistent messaging. Builds and maintains brand identity while developing strategies to reach target audiences and achieve business objectives.'),
(5, 'Financial Analyst', 'Evaluates financial data and market trends to provide insights for business planning and investment decisions. Prepares detailed financial reports, forecasts, and models to support strategic initiatives. Analyzes company performance metrics, industry benchmarks, and economic indicators to assess financial health. Conducts cost-benefit analyses for projects and potential investments. Identifies areas for operational efficiency and cost reduction. Presents findings to management with clear recommendations. Stays current with financial regulations and works closely with accounting, operations, and executive teams to optimize financial performance.'),
(6, 'Sales Manager', 'Leads and oversees sales teams to achieve revenue targets and business objectives. Develops sales strategies, sets goals, and establishes territories. Recruits, trains, and coaches sales representatives while monitoring performance metrics. Builds and maintains relationships with key clients and partners. Analyzes market trends, competitive landscape, and sales data to identify opportunities for growth. Collaborates with marketing, product development, and customer service departments to ensure customer satisfaction and retention.'),
(7, 'School Teacher', 'Educates students in specific subject areas, developing and implementing lesson plans that meet curriculum standards. Creates an engaging learning environment that accommodates diverse learning styles and abilities. Assesses student progress through assignments, projects, and tests. Communicates with parents regarding student performance and behavior. Participates in professional development activities and school community events. Adapts teaching methods to support individual student needs while fostering critical thinking, creativity, and social skills.'),
(8, 'Project Manager', 'Leads projects from conception to completion, ensuring deliverables meet requirements within established timelines and budgets. Develops project plans, allocates resources, and assigns tasks to team members. Identifies and mitigates risks while solving problems that arise during project execution. Facilitates communication among stakeholders, team members, and clients. Tracks progress using project management methodologies and tools. Conducts regular status meetings and generates reports for leadership. Evaluates project outcomes and documents lessons learned for continuous improvement.'),
(10, 'Human Resources Manager', 'Oversees the HR department and develops strategies aligned with organizational goals. Manages recruitment, onboarding, and retention processes. Develops and implements policies related to employment, compensation, benefits, and employee relations. Ensures compliance with labor laws and regulations. Addresses employee concerns and mediates workplace conflicts. Administers performance management systems and coordinates training and development programs. Partners with leadership to promote a positive workplace culture and organizational effectiveness.')
