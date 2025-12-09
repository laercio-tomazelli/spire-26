-- =============================================================================
-- SPIRE 26 - MariaDB Initialization
-- =============================================================================

-- Create test database for PHPUnit
CREATE DATABASE IF NOT EXISTS spire_testing;
GRANT ALL PRIVILEGES ON spire_testing.* TO 'spire'@'%';

-- Set default charset
ALTER DATABASE spire CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Flush privileges
FLUSH PRIVILEGES;
