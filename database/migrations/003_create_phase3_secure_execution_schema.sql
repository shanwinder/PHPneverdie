ALTER TABLE challenges
  ADD COLUMN runtime_enabled TINYINT(1) NOT NULL DEFAULT 0,
  ADD COLUMN runtime_mode ENUM('rule','output','testcase','manual','hybrid') NOT NULL DEFAULT 'rule',
  ADD COLUMN runtime_profile_id INT UNSIGNED NULL,
  ADD COLUMN run_button_enabled TINYINT(1) NOT NULL DEFAULT 0,
  ADD COLUMN submit_runtime_enabled TINYINT(1) NOT NULL DEFAULT 0;

CREATE TABLE IF NOT EXISTS runtime_profiles (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL UNIQUE,
  language VARCHAR(50) NOT NULL DEFAULT 'php',
  version VARCHAR(50) NULL,
  docker_image VARCHAR(190) NOT NULL,
  timeout_ms INT UNSIGNED NOT NULL DEFAULT 3000,
  memory_mb INT UNSIGNED NOT NULL DEFAULT 64,
  cpu_quota VARCHAR(50) NULL,
  network_enabled TINYINT(1) NOT NULL DEFAULT 0,
  max_output_bytes INT UNSIGNED NOT NULL DEFAULT 20000,
  max_code_bytes INT UNSIGNED NOT NULL DEFAULT 50000,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE challenges
  ADD CONSTRAINT fk_challenges_runtime_profile FOREIGN KEY (runtime_profile_id) REFERENCES runtime_profiles(id) ON DELETE SET NULL;

CREATE TABLE IF NOT EXISTS challenge_test_cases (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  challenge_id INT UNSIGNED NOT NULL,
  name VARCHAR(255) NOT NULL,
  input_data LONGTEXT NULL,
  expected_output LONGTEXT NULL,
  expected_pattern TEXT NULL,
  comparison_mode ENUM('exact','trimmed','contains','regex') NOT NULL DEFAULT 'trimmed',
  is_hidden TINYINT(1) NOT NULL DEFAULT 0,
  weight INT UNSIGNED NOT NULL DEFAULT 1,
  sort_order INT NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  CONSTRAINT fk_test_cases_challenge FOREIGN KEY (challenge_id) REFERENCES challenges(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS execution_jobs (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  challenge_id INT UNSIGNED NULL,
  submission_id INT UNSIGNED NULL,
  runtime_profile_id INT UNSIGNED NOT NULL,
  job_type ENUM('run','submit','sql_run','sql_submit') NOT NULL DEFAULT 'run',
  status ENUM('queued','running','passed','failed','error','timeout','cancelled') NOT NULL DEFAULT 'queued',
  code LONGTEXT NOT NULL,
  input_data LONGTEXT NULL,
  queued_at DATETIME NOT NULL,
  started_at DATETIME NULL,
  finished_at DATETIME NULL,
  worker_id VARCHAR(100) NULL,
  error_message TEXT NULL,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  INDEX idx_execution_status (status),
  INDEX idx_execution_user (user_id),
  INDEX idx_execution_challenge (challenge_id),
  CONSTRAINT fk_execution_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_execution_challenge FOREIGN KEY (challenge_id) REFERENCES challenges(id) ON DELETE SET NULL,
  CONSTRAINT fk_execution_submission FOREIGN KEY (submission_id) REFERENCES challenge_submissions(id) ON DELETE SET NULL,
  CONSTRAINT fk_execution_profile FOREIGN KEY (runtime_profile_id) REFERENCES runtime_profiles(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS execution_results (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  execution_job_id BIGINT UNSIGNED NOT NULL,
  stdout MEDIUMTEXT NULL,
  stderr MEDIUMTEXT NULL,
  exit_code INT NULL,
  duration_ms INT UNSIGNED NULL,
  memory_peak_kb INT UNSIGNED NULL,
  output_truncated TINYINT(1) NOT NULL DEFAULT 0,
  passed TINYINT(1) NOT NULL DEFAULT 0,
  result_summary TEXT NULL,
  created_at DATETIME NOT NULL,
  CONSTRAINT fk_execution_results_job FOREIGN KEY (execution_job_id) REFERENCES execution_jobs(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS execution_test_case_results (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  execution_job_id BIGINT UNSIGNED NOT NULL,
  test_case_id INT UNSIGNED NULL,
  test_name VARCHAR(255) NOT NULL,
  expected_output MEDIUMTEXT NULL,
  actual_output MEDIUMTEXT NULL,
  stderr MEDIUMTEXT NULL,
  exit_code INT NULL,
  passed TINYINT(1) NOT NULL DEFAULT 0,
  duration_ms INT UNSIGNED NULL,
  message TEXT NULL,
  created_at DATETIME NOT NULL,
  CONSTRAINT fk_case_results_job FOREIGN KEY (execution_job_id) REFERENCES execution_jobs(id) ON DELETE CASCADE,
  CONSTRAINT fk_case_results_case FOREIGN KEY (test_case_id) REFERENCES challenge_test_cases(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS sql_playgrounds (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  lesson_id INT UNSIGNED NULL,
  title VARCHAR(255) NOT NULL,
  slug VARCHAR(255) NOT NULL UNIQUE,
  description TEXT NOT NULL,
  schema_sql LONGTEXT NOT NULL,
  seed_sql LONGTEXT NOT NULL,
  expected_result_json LONGTEXT NULL,
  allowed_statement ENUM('select_only','insert_update_select','full_sandbox') NOT NULL DEFAULT 'select_only',
  xp_reward INT NOT NULL DEFAULT 50,
  is_published TINYINT(1) NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  CONSTRAINT fk_sql_playgrounds_lesson FOREIGN KEY (lesson_id) REFERENCES lessons(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS sql_submissions (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  sql_playground_id INT UNSIGNED NOT NULL,
  query_text LONGTEXT NOT NULL,
  status ENUM('passed','failed','error') NOT NULL DEFAULT 'failed',
  result_preview MEDIUMTEXT NULL,
  feedback TEXT NULL,
  submitted_at DATETIME NOT NULL,
  CONSTRAINT fk_sql_submissions_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_sql_submissions_playground FOREIGN KEY (sql_playground_id) REFERENCES sql_playgrounds(id) ON DELETE CASCADE,
  INDEX idx_sql_submission_user_playground (user_id, sql_playground_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS project_submissions (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  title VARCHAR(255) NOT NULL,
  description TEXT NULL,
  submission_type ENUM('github_url','zip_upload','text_note') NOT NULL DEFAULT 'github_url',
  github_url VARCHAR(500) NULL,
  zip_path VARCHAR(500) NULL,
  note LONGTEXT NULL,
  status ENUM('draft','submitted','under_review','approved','rejected','revision_requested') NOT NULL DEFAULT 'submitted',
  submitted_at DATETIME NULL,
  reviewed_at DATETIME NULL,
  reviewed_by INT UNSIGNED NULL,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  CONSTRAINT fk_project_submissions_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_project_submissions_reviewer FOREIGN KEY (reviewed_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS project_reviews (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  project_submission_id BIGINT UNSIGNED NOT NULL,
  reviewer_id INT UNSIGNED NOT NULL,
  database_score INT UNSIGNED NOT NULL DEFAULT 0,
  security_score INT UNSIGNED NOT NULL DEFAULT 0,
  ui_score INT UNSIGNED NOT NULL DEFAULT 0,
  code_structure_score INT UNSIGNED NOT NULL DEFAULT 0,
  feature_score INT UNSIGNED NOT NULL DEFAULT 0,
  total_score INT UNSIGNED NOT NULL DEFAULT 0,
  feedback LONGTEXT NULL,
  decision ENUM('approved','rejected','revision_requested') NOT NULL,
  reviewed_at DATETIME NOT NULL,
  CONSTRAINT fk_project_reviews_submission FOREIGN KEY (project_submission_id) REFERENCES project_submissions(id) ON DELETE CASCADE,
  CONSTRAINT fk_project_reviews_reviewer FOREIGN KEY (reviewer_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS certificates (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  course_id INT UNSIGNED NOT NULL,
  project_submission_id BIGINT UNSIGNED NULL,
  certificate_number VARCHAR(100) NOT NULL UNIQUE,
  verification_code VARCHAR(100) NOT NULL UNIQUE,
  title VARCHAR(255) NOT NULL,
  issued_at DATETIME NOT NULL,
  revoked_at DATETIME NULL,
  revoke_reason TEXT NULL,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  CONSTRAINT fk_certificates_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_certificates_course FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
  CONSTRAINT fk_certificates_project FOREIGN KEY (project_submission_id) REFERENCES project_submissions(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS audit_logs (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NULL,
  action VARCHAR(150) NOT NULL,
  entity_type VARCHAR(100) NULL,
  entity_id VARCHAR(100) NULL,
  ip_address VARCHAR(100) NULL,
  user_agent VARCHAR(500) NULL,
  metadata_json LONGTEXT NULL,
  created_at DATETIME NOT NULL,
  INDEX idx_audit_action (action),
  INDEX idx_audit_user (user_id),
  CONSTRAINT fk_audit_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

