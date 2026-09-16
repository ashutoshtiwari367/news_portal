-- ============================================================
--  NEWS PORTAL - Complete Database Setup
--  Database: news_portal
--  Run this file in phpMyAdmin or MySQL CLI
-- ============================================================

-- CREATE DATABASE IF NOT EXISTS `u447123054_news_portal`
--     CHARACTER SET utf8mb4
--     COLLATE utf8mb4_unicode_ci;

-- USE `u447123054_news_portal`;

-- ============================================================
-- TABLE: admins
-- ============================================================

CREATE TABLE IF NOT EXISTS `admins` (
    `id`         INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name`       VARCHAR(150) NOT NULL,
    `email`      VARCHAR(200) NOT NULL UNIQUE,
    `password`   VARCHAR(255) NOT NULL,
    `role`       ENUM('superadmin','editor') NOT NULL DEFAULT 'editor',
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Default admin: admin@news.com / admin123
INSERT INTO `admins` (`name`, `email`, `password`, `role`) VALUES
('Super Admin', 'admin@news.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'superadmin');


-- ============================================================
-- TABLE: categories
-- ============================================================

CREATE TABLE IF NOT EXISTS `categories` (
    `id`         INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name`       VARCHAR(100) NOT NULL,
    `slug`       VARCHAR(120) NOT NULL UNIQUE,
    `status`     TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `categories` (`name`, `slug`, `status`) VALUES
('National',     'national',     1),
('International','international',1),
('Politics',     'politics',     1),
('Business',     'business',     1),
('Technology',   'technology',   1),
('Sports',       'sports',       1),
('Entertainment','entertainment',1),
('Health',       'health',       1);


-- ============================================================
-- TABLE: news
-- ============================================================

CREATE TABLE IF NOT EXISTS `news` (
    `id`                INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `category_id`       INT(11) UNSIGNED NOT NULL,
    `title`             VARCHAR(300) NOT NULL,
    `slug`              VARCHAR(350) NOT NULL UNIQUE,
    `short_description` TEXT,
    `content`           LONGTEXT,
    `featured_image`    VARCHAR(255) DEFAULT NULL,
    `author_name`       VARCHAR(150) NOT NULL DEFAULT 'Admin',
    `status`            ENUM('published','draft') NOT NULL DEFAULT 'draft',
    `is_featured`       TINYINT(1) NOT NULL DEFAULT 0,
    `is_trending`       TINYINT(1) NOT NULL DEFAULT 0,
    `views`             INT(11) UNSIGNED NOT NULL DEFAULT 0,
    `published_at`      DATETIME DEFAULT NULL,
    `created_at`        TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`        TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_category`   (`category_id`),
    KEY `idx_status`     (`status`),
    KEY `idx_featured`   (`is_featured`),
    KEY `idx_trending`   (`is_trending`),
    KEY `idx_published`  (`published_at`),
    CONSTRAINT `fk_news_category` FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Demo News Articles
INSERT INTO `news` (`category_id`,`title`,`slug`,`short_description`,`content`,`author_name`,`status`,`is_featured`,`is_trending`,`views`,`published_at`) VALUES

-- National (category 1)
(1,
 'Prime Minister Launches New National Infrastructure Plan Worth ₹10 Lakh Crore',
 'pm-launches-national-infrastructure-plan',
 'The Prime Minister today unveiled a landmark infrastructure development plan targeting roads, railways, and digital connectivity across the country over the next five years.',
 '<p>In a landmark announcement, the Prime Minister today unveiled an ambitious national infrastructure development plan worth ₹10 lakh crore, aimed at transforming the country\'s road, railway, and digital connectivity network over the next five years.</p><p>The plan, dubbed "InfraBharat 2030," is expected to create over 2 crore jobs and boost the GDP by nearly 1.5%. Key highlights include the construction of 50,000 km of new highways, expansion of high-speed rail corridors, and the rollout of 5G connectivity to every district headquarters.</p><p>Speaking at the launch ceremony, the Prime Minister said, "This is not just an infrastructure plan — it is a vision for a self-reliant, modern India." The announcement was met with widespread appreciation from industry leaders and economists alike.</p>',
 'Admin', 'published', 1, 1, 15420, NOW() - INTERVAL 1 HOUR),

(1,
 'Heavy Monsoon Rains Lash Northern States; Red Alert Issued in 5 Districts',
 'heavy-monsoon-rains-northern-states-red-alert',
 'Intense monsoon rainfall has triggered flooding and landslides across northern states, with authorities issuing a red alert in five districts and deploying NDRF teams.',
 '<p>Heavy monsoon rains continued to batter northern India today, triggering flash floods and landslides in several districts. The Meteorological Department has issued a red alert for five districts and warned of more rainfall over the next 48 hours.</p><p>The National Disaster Response Force (NDRF) has deployed 12 teams across affected areas, and rescue operations are underway. Several highways have been blocked due to landslides, disrupting transportation and supply chains.</p><p>Chief Ministers of affected states have called emergency meetings and urged residents to stay indoors. Relief camps have been set up to accommodate displaced families.</p>',
 'Rajesh Kumar', 'published', 1, 0, 8900, NOW() - INTERVAL 3 HOUR),

-- International (category 2)
(2,
 'G20 Summit Concludes with Historic Agreement on Climate Finance',
 'g20-summit-climate-finance-agreement',
 'World leaders at the G20 summit reached a landmark agreement to mobilize $1 trillion annually for climate finance, marking a major step in the global fight against climate change.',
 '<p>The G20 Summit concluded in New Delhi today with world leaders signing a historic agreement to mobilize $1 trillion annually in climate finance by 2030. The deal, described as "transformational" by global climate experts, commits developed nations to significantly scale up financial support for developing countries transitioning to clean energy.</p><p>India played a pivotal role in brokering the deal, with the Prime Minister receiving widespread acclaim for his leadership. The agreement also includes provisions for technology transfer and capacity building.</p><p>"This is a turning point in the fight against climate change," said the UN Secretary-General. "For the first time, we have a concrete financial commitment that matches the scale of the challenge."</p>',
 'Priya Sharma', 'published', 1, 1, 22100, NOW() - INTERVAL 2 HOUR),

(2,
 'US Federal Reserve Holds Interest Rates Steady Amid Economic Uncertainty',
 'us-federal-reserve-holds-interest-rates-steady',
 'The US Federal Reserve decided to keep interest rates unchanged at its latest policy meeting, citing mixed economic signals and ongoing uncertainty in global markets.',
 '<p>The US Federal Reserve announced Wednesday that it would hold its benchmark interest rate steady, pausing its rate-hiking cycle amid mixed economic data and concerns about global growth. The decision was unanimous among the committee members.</p><p>Fed Chair Jerome Powell said the central bank remains committed to bringing inflation back to its 2% target but acknowledged that the economy has shown surprising resilience. Futures markets had widely anticipated the pause, and stock markets responded positively to the announcement.</p>',
 'Anita Verma', 'published', 0, 1, 11200, NOW() - INTERVAL 5 HOUR),

-- Technology (category 5)
(5,
 'India Becomes World\'s Second Largest Smartphone Manufacturer',
 'india-second-largest-smartphone-manufacturer',
 'India has overtaken China to become the world\'s second largest smartphone manufacturing hub, with exports crossing $15 billion in the current fiscal year.',
 '<p>India has achieved a remarkable milestone in electronics manufacturing, becoming the world\'s second largest smartphone manufacturer after China. Smartphone exports crossed $15 billion in the current fiscal year, a 45% jump from the previous year.</p><p>The achievement is attributed to the Production Linked Incentive (PLI) scheme for electronics, which attracted global manufacturers including Apple, Samsung, and multiple domestic brands to set up large-scale assembly and manufacturing operations in India.</p><p>Industry analysts project that India could surpass China in global smartphone exports within the next decade, given its growing manufacturing ecosystem and cost competitiveness.</p>',
 'Tech Desk', 'published', 1, 1, 31500, NOW() - INTERVAL 4 HOUR),

(5,
 'ISRO Successfully Tests Next-Generation Rocket Engine for Gaganyaan Mission',
 'isro-tests-next-gen-rocket-engine-gaganyaan',
 'ISRO has successfully completed a critical test of its CE-20 cryogenic engine that will power the Gaganyaan human spaceflight mission, marking a major milestone.',
 '<p>The Indian Space Research Organisation (ISRO) on Thursday successfully conducted a long-duration hot test of its CE-20 cryogenic engine at the ISRO Propulsion Complex in Mahendragiri, Tamil Nadu. The test lasted 720 seconds, meeting all performance parameters.</p><p>The CE-20 engine will be used in the upper stage of the LVM3 rocket that will carry Indian astronauts to space in the Gaganyaan mission. Chairman of ISRO said this was a critical milestone in India\'s first human spaceflight program.</p>',
 'Science Desk', 'published', 0, 0, 9800, NOW() - INTERVAL 6 HOUR),

-- Sports (category 6)
(6,
 'Team India Wins Test Series Against Australia 3-1 in Historic Victory',
 'india-wins-test-series-australia',
 'The Indian cricket team clinched a historic 3-1 Test series victory against Australia on Australian soil, with a dominant performance by the pace bowling attack.',
 '<p>Team India scripted history on Monday by clinching the Border-Gavaskar Test series 3-1 against Australia at the Melbourne Cricket Ground. This is India\'s third consecutive series victory in Australia and confirms their top position in the ICC World Test Championship standings.</p><p>Indian captain Rohit Sharma was effusive in his praise for the team: "This is a collective effort. Our bowlers were exceptional and our batters showed great character." Star performer Jasprit Bumrah finished the series with 32 wickets at an average of 13.2.</p>',
 'Sports Desk', 'published', 1, 1, 45000, NOW() - INTERVAL 1 DAY),

(6,
 'Neeraj Chopra Sets New National Record in Javelin Throw at Diamond League',
 'neeraj-chopra-national-record-diamond-league',
 'Olympic gold medalist Neeraj Chopra has set a new national record with a stunning throw of 89.45 meters at the Diamond League meet in Zurich.',
 '<p>India\'s golden boy Neeraj Chopra shattered his own national record with a massive throw of 89.45 meters at the Diamond League Final in Zurich, Switzerland. The throw also earned him the Diamond Trophy, the sport\'s most prestigious circuit prize.</p><p>The 26-year-old athlete said he was thrilled with his performance and is targeting the 90-meter barrier in the upcoming season. He dedicated the achievement to his coaches and the Sports Authority of India for their unwavering support.</p>',
 'Sports Desk', 'published', 0, 1, 28900, NOW() - INTERVAL 2 DAY),

-- Business (category 4)
(4,
 'Sensex Crosses 85,000 Mark for First Time in History',
 'sensex-crosses-85000-mark-history',
 'The BSE Sensex breached the historic 85,000 level for the first time ever, driven by strong buying in IT, banking, and consumer discretionary sectors.',
 '<p>The BSE Sensex crossed the historic 85,000 mark for the first time in its history during intraday trade on Thursday, fueled by strong foreign institutional investor (FII) buying and positive global cues. The Nifty50 also touched a record high of 25,900.</p><p>Experts attributed the rally to improving macroeconomic fundamentals, strong corporate earnings, and expectations of a US Federal Reserve rate cut. Banking, IT, and consumer stocks led the rally.</p><p>Market analysts remain bullish on the long-term outlook for Indian equities, citing strong GDP growth, a young demographic, and increasing domestic retail participation in stock markets.</p>',
 'Finance Desk', 'published', 1, 1, 19400, NOW() - INTERVAL 8 HOUR),

-- Health (category 8)
(8,
 'AIIMS Develops Revolutionary Gene Therapy for Sickle Cell Anaemia',
 'aiims-gene-therapy-sickle-cell-anaemia',
 'Doctors at AIIMS New Delhi have developed an affordable gene therapy that has shown 90% success rates in treating sickle cell anaemia in clinical trials.',
 '<p>In a groundbreaking medical achievement, researchers at the All India Institute of Medical Sciences (AIIMS) New Delhi have developed an affordable gene therapy for sickle cell anaemia that has demonstrated a 90% success rate in Phase 2 clinical trials involving 150 patients.</p><p>The therapy, which costs a fraction of currently available treatments abroad, works by correcting the genetic mutation responsible for the disease. Dr. Vandana Rao, who led the research team, said this could be a game-changer for millions of patients in India and Africa.</p>',
 'Health Desk', 'published', 0, 0, 7200, NOW() - INTERVAL 10 HOUR),

-- Entertainment (category 7)
(7,
 'Bollywood Blockbuster Crosses ₹1000 Crore at Global Box Office',
 'bollywood-blockbuster-1000-crore-global-box-office',
 'The much-awaited Bollywood action thriller has crossed the ₹1000 crore mark at the global box office within just 10 days of its release, setting new records.',
 '<p>The Bollywood mega-blockbuster entered the exclusive ₹1000 crore club globally within just 10 days of its release, shattering multiple box office records. The film has become the fastest Indian film to achieve this milestone.</p><p>The film, featuring a high-octane storyline with stunning visual effects, has been praised by critics and audiences alike. Director Sanjay Raut said he was overwhelmed by the response and dedicated the success to his cast and crew who worked tirelessly on the project for over three years.</p>',
 'Entertainment Desk', 'published', 0, 1, 35600, NOW() - INTERVAL 3 DAY);


-- ============================================================
-- TABLE: breaking_news
-- ============================================================

CREATE TABLE IF NOT EXISTS `breaking_news` (
    `id`         INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `title`      VARCHAR(300) NOT NULL,
    `link`       VARCHAR(500) DEFAULT '#',
    `status`     TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `breaking_news` (`title`, `link`, `status`) VALUES
('PM launches ₹10 lakh crore infrastructure plan — InfraBharat 2030', '#', 1),
('Sensex crosses 85,000 for first time in history', '#', 1),
('India wins Test series against Australia 3-1 in historic win', '#', 1),
('ISRO successfully tests CE-20 cryogenic engine for Gaganyaan', '#', 1),
('G20 reaches landmark $1 trillion climate finance deal', '#', 1);


-- ============================================================
-- TABLE: settings
-- ============================================================

CREATE TABLE IF NOT EXISTS `settings` (
    `id`            INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `setting_key`   VARCHAR(100) NOT NULL UNIQUE,
    `setting_value` TEXT,
    `created_at`    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `settings` (`setting_key`, `setting_value`) VALUES
('site_name',      'NewsPortal'),
('site_tagline',   'Latest News & Breaking Updates'),
('contact_email',  'info@newsportal.com'),
('facebook_url',   'https://facebook.com'),
('instagram_url',  'https://instagram.com'),
('twitter_url',    'https://twitter.com'),
('youtube_url',    'https://youtube.com');
