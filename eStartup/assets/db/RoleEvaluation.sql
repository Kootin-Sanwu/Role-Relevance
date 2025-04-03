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
(0, 'Reporting and Data Analyst', 'Company: Stone Depot Ghana Limited | Industry: Construction | Description: Extract, compile, and analyze data from internal systems, including CRM, ERP, and inventory management tools. Stone Depot Limited is a natural stone and fabrication company known for delivering high-quality materials to clients with a commitment to excellence and precision, we utilize data-driven insights to streamline operations and enhance decision-making. We are looking for a talented reporting and data analyst to manage and analyze data, create detailed reports, and present actionable insights that drive our business forward. If you are passionate about transforming raw data into meaningful information and are skilled in Google Looker Studio or equivalent software, we love to hear from you.'),
(1, 'Software Engineer Trainee', 'Company: Zedulo Ghana Ltd. | Industry: IT & Telecoms | Description: Are you well versed in software development, preferably with a university degree in computer science? Have you completed your national service? We are looking for you!'),
(2, 'Marketing Communication Specialist', 'Company: Jobberman | Industry: Third Party Recruitment | Description: Work across the organizations'' focal points to identify media moments, success stories, develop and implement relevant materials and media outreach. The Communications Specialist will play a vital role in the transformative Harnessing Agricultural Productivity and Prosperity for Youth (HAPPY) program. The primary objective is to enhance the well-being, resilience, and active engagement of youth - especially women, in Ghana''s formal agribusiness and related sectors.'),
(3, 'Sales Executive', 'Company: Geldex Invest | Industry: Banking, Finance & Insurance | Description: Geldex is a leading online forex trading brokerage firm that provides online trading services to clients in Ghana, and worldwide. We''re seeking an experienced Sales Executive to join our team, and contribute to the growth and success of our company.'),
(4, 'Chief Executive Officer', 'Company: G-Maestro Limited | Industry: Education | Description: A distinguished college focusing on standardized test (IELTS, SAT, TOEFL, GRE, GMAT, OET, NCLEX), study abroad consultancy, Cambridge IGCSE & A-Level, WASSCE, English proficiency and Digital Technology is seeking an experienced and visionary Chief Executive Officer (CEO) to lead our institution. The CEO will play a pivotal role in establishing and managing the institution.'),
(5, 'Travel and Tour Marketer', 'Company: Travelclazz | Industry: Tourism & Travel | Description: Identify potential customers through various channels such as social media, networking events, referrals, and partnerships. As a Travel and Tour Marketer, you will promote and sell travel packages, tours, flights tickets, and related services to potential clients. Your role will involve generating leads, building relationships, and closing sales while earning commission-based compensation in USD. You will represent the company, ensuring a positive experience for clients by delivering excellent customer service and tailored travel solutions.'),
(6, 'Sales Attendant', 'Company: Cake Matters | Industry: Retail, Fashion & FMCG | Description: As a Sales Attendant, you will be responsible for finding and winning new customers, as well as looking after existing customer accounts.'),
(7, 'Business Manager', 'Company: Personnel Practice Limited | Industry: Construction | Description: A diversified business with interests in property, financial services and technology is looking for a competent, driven and experienced individual for the role of Business Manager.'),
(8, 'Financial Analyst', 'Company: DTRT Apparel | Industry: Manufacturing & Warehousing | Description: A reputable company is seeking the services of a Financial Analyst. The Financial Analyst would work with the finance team and report directly to Manager Financial Planning & Analysis.'),
(9, 'Web Developer', 'Company: Sneda | Industry: Retail, Fashion & FMCG | Description: Based in Accra, we''re a fast-growing team passionate about building intuitive, high-performance digital experiences. We''re looking for a talented Web Developer to join us and help craft the next generation of websites and applications.'),
(10, 'UI/UX Designer', 'Company: One Dot World | Industry: IT & Telecoms | Description: OneDotWorld is looking for a talented UI/UX Designer to join our team and help create user-centric, visually stunning, and seamless digital experiences.'),
(11, 'Head of Commercial', 'Company: Jobberman | Industry: Third Party Recruitment | Description: Identify market opportunities at the account level and run a data-driven sales planning process, set smart quotas, and design compelling incentive plans. You form an integral part of our ecosystems by executing on the commercial strategy. You will be integral in the generation and execution of this strategy in a bid to bridge the talent gap in Africa. You will hold the strategic mandate to plan and run the commercial side of the business with best-in-class processes by analyzing market trends, forecasting sales, and implementing strategies to maximize profitability. You will partner with the CEO to drive business growth, ensuring operational efficiency, and fostering successful customer relationships. Ideally you must possess strong analytical skills, a deep understanding of market dynamics, and the ability to communicate and collaborate effectively across various teams within the organization.'),
(12, 'Accountant', 'Company: No name provided | Industry: Education | Description: We are seeking an Accountant. Your duties include; preparation of reports and inventory management. The ideal candidate must have knowledge about Tally software and assist in any other duties that will be provided.'),
(13, 'Driver (Type C)', 'Company: Penta Foods | Industry: Retail, Fashion & FMCG | Description: Safely and punctually deliver products to assigned shops, resellers, outlets, and individual customers. We are hiring a Driver with license Type C to safely and punctually deliver products to assigned clients, ensure proper maintenance of the delivery vehicle, ensure accurate and complete documentation for all deliveries while communicating effectively with customers ensuring a positive customer experience.'),
(14, 'Human Resource Officer', 'Company: Agenda Commercial Ltd | Industry: Retail, Fashion & FMCG | Description: We are seeking a highly skilled and experienced Human Resource Manager to oversee all aspects of our HR functions in our trading and general goods business. The HR Manager will be responsible for managing recruitment, employee relations, performance management, compliance, and driving digital transformation within the HR department.'),
(15, 'Business Development Manager', 'Company: Jobberman | Industry: Manufacturing & Warehousing | Description: To be ambitious and energetic Business Development Manager who can help us expand our clientele. You will be the front of the company and will have the dedication to create and apply an effective sales strategy.'),
(16, 'Head of Commercial', 'Company: Jobberman | Industry: Third Party Recruitment | Description: Identify market opportunities at the account level and run a data-driven sales planning process, set smart quotas, and design compelling incentive plans. You form an integral part of our ecosystems by executing on the commercial strategy. You will be integral in the generation and execution of this strategy in a bid to bridge the talent gap in Africa. You will hold the strategic mandate to plan and run the commercial side of the business with best-in-class processes by analyzing market trends, forecasting sales, and implementing strategies to maximize profitability. You will partner with the CEO to drive business growth, ensuring operational efficiency, and fostering successful customer relationships. Ideally you must possess strong analytical skills, a deep understanding of market dynamics, and the ability to communicate and collaborate effectively across various teams within the organization.'),
(17, 'Reporting and Data Analyst', 'Company: Stone Depot Ghana Limited | Industry: Construction | Description: Extract, compile, and analyze data from internal systems, including CRM, ERP, and inventory management tools. Stone Depot Limited is a natural stone and fabrication company known for delivering high-quality materials to clients with a commitment to excellence and precision, we utilize data-driven insights to streamline operations and enhance decision-making. We are looking for a talented reporting and data analyst to manage and analyze data, create detailed reports, and present actionable insights that drive our business forward. If you are passionate about transforming raw data into meaningful information and are skilled in Google Looker Studio or equivalent software, we love to hear from you.'),
(18, 'Senior Software Engineer - Packaging - Optimize Ubuntu Server', 'Company: No name provided | Industry: Not specified | Description: No description provided'),
(19, 'IT Support Officer', 'Company: No name provided | Industry: Banking, Finance & Insurance | Description: The holder of the role is responsible for administering all databases, networking and systems, including but not limited to, JBase databases, SQL databases and business applications, the holder will also set up and administer, configure backup solutions. In addition, serve as a technical expert in the area of system and network administration for complex operating systems amongst others.'),
(20, 'Analyst, Cybersecurity at MTN Ghana', 'Company: No name provided | Industry: Not specified | Description: No description provided'),
(21, 'Cloud Field Engineering Manager', 'Company: No name provided | Industry: Not specified | Description: No description provided'),
(22, 'Senior DevOps Engineer at AmaliTech', 'Company: No name provided | Industry: Not specified | Description: No description provided'),
(23, 'Full-stack Drupal Developer', 'Company: Fjorge | Industry: IT & Telecoms | Description: Development communication we are looking for a full-stack developer with extensive Drupal experience to join our team. The Full-Stack Developer role at Fjorge is integral to delivering high-quality products that meet client needs and business goals. As a leader on the development team, the Sr. Developer ensures the technical execution of projects, from architecture to estimation to completion. The Sr. Developer collaborates closely with other team members, consistently providing clear communication, accurate estimations, and maintaining a high standard of work that aligns with the product vision. This is a in-person role based in our Kumasi, Ghana office. We expect to see full time in-office collaboration.'),
(24, 'Full-stack Blockchain Developer', 'Company: No name provided | Industry: Not specified | Description: No description provided'),
(25, 'UI/UX Designer', 'Company: One Dot World | Industry: IT & Telecoms | Description: OneDotWorld is looking for a talented UI/UX Designer to join our team and help create user-centric, visually stunning, and seamless digital experiences.'),
(26, 'Data Engineer', 'Company: No name provided | Industry: Not specified | Description: No description provided');
