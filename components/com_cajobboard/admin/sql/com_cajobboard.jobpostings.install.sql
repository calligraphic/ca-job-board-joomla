/**
 * @package   Calligraphic Job Board
 * @version   0.1 May 1, 2018
 * @author    Calligraphic, LLC http://www.calligraphic.design
 * @copyright Copyright (C) 2018 Calligraphic, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 * DEPENDS ON: #__cajobboard_ucm, #__cajobboard_places, #__cajobboard_organizations
 */

/**
 * Occupational category groups table
 *
 * Used to create general groups of job categories, e.g. "Office Staff",
 * "Leasing and Sales"
 */
CREATE TABLE IF NOT EXISTS `#__cajobboard_job_occupational_category_groups` (
  job_occupational_category_group_id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Surrogate primary key',
  /* SCHEMA: Thing */
  description TEXT NOT NULL COMMENT 'Occupational category group description',
  url VARCHAR(2083) NOT NULL COMMENT 'link to schema for occupational category, e.g. wikipedia page on Full Time',
  /* SCHEMA: https://calligraphic.design/schema/OccupationalCategoryBLS */
  `group` CHAR(50) NOT NULL COMMENT 'Group this occupational category should be shown under e.g. office staff',
  /* SQL DDL */
  PRIMARY KEY (job_occupational_category_group_id)
)
  ENGINE=innoDB
  DEFAULT CHARACTER SET = utf8
  DEFAULT COLLATE = utf8_unicode_ci;

/**
 * BLS O*NET-SOC taxonomy table for occupational categories
 *
 * see http://www.onetcenter.org/taxonomy.html
 */
CREATE TABLE IF NOT EXISTS `#__cajobboard_job_occupational_categories` (
  job_occupational_category_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Surrogate primary key',
  /* UCM (unified content model) properties for internal record metadata */
  ordering SMALLINT UNSIGNED NOT NULL DEFAULT '0' COMMENT 'order this job category should show in the group',
  /* SCHEMA: https://calligraphic.design/schema/OccupationalCategoryBLS */
  title CHAR(255) NOT NULL COMMENT 'occupational category title',
 	`code` CHAR(10) NOT NULL DEFAULT '0' COMMENT 'BLS code specifying this job category',
  `group` BIGINT UNSIGNED NOT NULL COMMENT 'group this occupational category should be shown under e.g. office staff', /* FK to #__cajobboard_job_occupational_category_group(job_occupational_category_group_id) */
  /* SQL DDL */
  PRIMARY KEY (job_occupational_category_id),
  UNIQUE KEY (title),
  INDEX job_category_group_index (`group`)
)
  ENGINE=innoDB
  DEFAULT CHARACTER SET = utf8
  DEFAULT COLLATE = utf8_unicode_ci;

/**
 * Employment type table
 *
 * Used to enumerate types of employment, e.g. "full-time", "part-time",
 * "contract", "temporary", "seasonal", "internship"
 */
CREATE TABLE IF NOT EXISTS `#__cajobboard_job_employment_types` (
  job_employment_type_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Surrogate primary key',
  /* SCHEMA: Thing */
  `name` CHAR(20) NOT NULL COMMENT 'Type of employment (e.g. full-time, part-time, contract, temporary, seasonal, internship)',
  description TEXT NOT NULL COMMENT 'Detailed description about type of employment',
  url VARCHAR(2083) NOT NULL COMMENT 'Link to schema for type of employment, e.g. wikipedia page on Full Time',
  /* SQL DDL */
  PRIMARY KEY (job_employment_type_id),
  UNIQUE KEY (`name`)
)
  ENGINE=innoDB
  DEFAULT CHARACTER SET = utf8
  DEFAULT COLLATE = utf8_unicode_ci;

/**
 * Job Postings table
 *
 * Uses schema https://schema.org/JobPosting
 */
CREATE TABLE IF NOT EXISTS `#__cajobboard_job_postings` (
  /* UCM (unified content model) properties for internal record metadata */
  job_posting_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Surrogate primary key',
  slug CHAR(50) NOT NULL COMMENT 'alias for SEF URL',
  /* FOF "magic" fields */
  asset_id	INT UNSIGNED NOT NULL DEFAULT '0' COMMENT 'FK to the #__assets table for access control purposes.',
  access INT UNSIGNED NOT NULL DEFAULT '0' COMMENT 'The Joomla! view access level.',
  enabled TINYINT NOT NULL DEFAULT '0' COMMENT 'Publish status: -2 for trashed and marked for deletion, -1 for archived, 0 for unpublished, and 1 for published.',
  created_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Timestamp of record creation, auto-filled by save().',
  created_by INT NOT NULL DEFAULT '0' COMMENT 'User ID who created the record, auto-filled by save().',
  modified_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Timestamp of record modification, auto-filled by save(), touch().',
  modified_by INT NOT NULL DEFAULT '0' COMMENT 'User ID who modified the record, auto-filled by save(), touch().',
  locked_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Timestamp of record locking, auto-filled by lock(), unlock().',
  locked_by INT NOT NULL DEFAULT '0' COMMENT 'User ID who locked the record, auto-filled by lock(), unlock().',
  /* Joomla UCM fields, used by Joomla!s UCM when using the FOF ContentHistory behaviour */
  publish_up DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Date and time to change the state to published, schema.org alias is datePosted.',
  publish_down DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Date and time to change the state to unpublished.',
  version INT UNSIGNED NOT NULL DEFAULT '1' COMMENT 'Version of this item.',
  ordering INT NOT NULL DEFAULT '0' COMMENT 'Order this record should appear in for sorting.',
  metadata JSON COMMENT 'JSON encoded metadata field for this item.',
  metakey TEXT NOT NULL COMMENT 'Meta keywords for this item.',
  metadesc TEXT NOT NULL COMMENT 'Meta descriptionfor this item.',
  xreference TEXT NOT NULL COMMENT 'A reference to enable linkages to external data sets, used to output a meta tag like FB open graph.',
  params TEXT NOT NULL COMMENT 'JSON encoded parameters for the content item.',
  language CHAR(7) NOT NULL COMMENT 'The language code for the article or * for all languages.',
  cat_id INT UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Category ID for this content item.',
  hits INT UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Number of hits the content item has received on the site.',
  featured TINYINT UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Whether this content item is featured or not.',
  /* SCHEMA: JobPosting */
  title CHAR(255) COMMENT 'The title of the job posting.',
  disambiguating_description TEXT COMMENT 'Short description of the job, used in cajobboard on job posting list pages, etc.',
  description TEXT COMMENT 'Long description of the job posting.',
  education_requirements TEXT COMMENT 'Educational background needed for the position or Occupation.',
  experience_requirements TEXT COMMENT 'Description of skills and experience needed for the position or Occupation.',
  incentive_compensation TEXT COMMENT 'Description of bonus and commission compensation aspects of the job. Supersedes incentives.',
  job_benefits TEXT COMMENT 'Description of benefits associated with the job. Supersedes benefits.',
  qualifications TEXT COMMENT 'Specific qualifications required for this role or Occupation.',
  responsibilities TEXT COMMENT 'Responsibilities associated with this role or Occupation.',
  skills TEXT COMMENT 'Skills required to fulfill this role',
  special_commitments TEXT COMMENT 'Any special commitments associated with this job posting. Valid entries include VeteranCommit, MilitarySpouseCommit, etc.',
  work_hours TEXT COMMENT 'The typical working hours for this job (e.g. 1st shift, night shift, 8am-5pm).', /* OpeningHoursSpecification has a schema very close to something workHours could use */
  job_location BIGINT UNSIGNED NOT NULL COMMENT 'A (typically single) geographic location associated with the job position.', /* FK to #__cajobboard_places(place_id) */
  /* SCHEMA: JobPosting (relevantOccupation) -> Occupation (name) */
  relevant_occupation_name TEXT NOT NULL COMMENT 'The job title.',
  /* SCHEMA: JobPosting (baseSalary) -> MonetaryAmount */
  base_salary__max_value INT UNSIGNED COMMENT 'The maximum salary of the job or of an employee in an EmployeeRole.',
  base_salary__value INT UNSIGNED COMMENT 'The base salary of the job or of an employee in an EmployeeRole.',
  base_salary__min_value INT UNSIGNED COMMENT 'The minimum salary of the job or of an employee in an EmployeeRole.',
  base_salary__currency CHAR(6) COMMENT 'Use ISO 4217 currency format e.g. USD.',
  /* SCHEMA: JobPosting (baseSalary) -> MonetaryAmount (additionalType) -> Duration */
  base_salary__duration CHAR(32) COMMENT 'use ISO 8601 duration format, e.g. P2W for bi-weekly.',
  hiring_organization BIGINT UNSIGNED NOT NULL COMMENT 'Organization offering the job position.', /* FK to #__cajobboard_organizations(cajobboard_organization_id) */
  /* SCHEMA: https://calligraphic.design/schema/EmploymentType */
  employment_type BIGINT UNSIGNED NOT NULL COMMENT 'Type of employment (e.g. full-time, part-time, contract, temporary, seasonal, internship).', /* FK to #__cajobboard_jobemploymenttypes(cajobboard_jobemploymenttype_id) */
  /* SCHEMA: https://calligraphic.design/schema/OccupationalCategoryBLS */
  occupational_category BIGINT UNSIGNED NOT NULL COMMENT 'The occupation of the job posting. Uses BLS O*NET-SOC taxonomy.', /* FK to #__cajobboard_jobOccupationalCategories(cajobboard_joboccupationalcategory_id) */
  /* SQL DDL */
  PRIMARY KEY (job_posting_id),
  INDEX jobposting_slug_index (slug),
  INDEX occupational_category_index (occupational_category),
  INDEX job_location_index (job_location),
  INDEX hiring_organization_index (hiring_organization)
)
  ENGINE=innoDB
  DEFAULT CHARACTER SET = utf8
  DEFAULT COLLATE = utf8_unicode_ci;

/**
 * Initial employment types
 */
INSERT INTO `#__cajobboard_job_employment_types` (name, description, url) VALUES
  ('COM_CAJOBBOARD_EMPLOYMENT_TYPE_FULL_TIME', 'COM_CAJOBBOARD_EMPLOYMENT_TYPE_FULL_TIME_DESCRIPTION', 'https://en.wikipedia.org/wiki/Full-time'),
  ('COM_CAJOBBOARD_EMPLOYMENT_TYPE_PART_TIME', 'COM_CAJOBBOARD_EMPLOYMENT_TYPE_PART_TIME_DESCRIPTION', 'https://en.wikipedia.org/wiki/Part-time_contract'),
  ('COM_CAJOBBOARD_EMPLOYMENT_TYPE_FLEX-TIME', 'COM_CAJOBBOARD_EMPLOYMENT_TYPE_FLEX-TIME_DESCRIPTION', 'https://en.wikipedia.org/wiki/Flextime'),
  ('COM_CAJOBBOARD_EMPLOYMENT_TYPE_CONTRACT', 'COM_CAJOBBOARD_EMPLOYMENT_TYPE_CONTRACT_DESCRIPTION', 'https://en.wikipedia.org/wiki/Fixed-term_employment_contract'),
  ('COM_CAJOBBOARD_EMPLOYMENT_TYPE_TEMPORARY', 'COM_CAJOBBOARD_EMPLOYMENT_TYPE_TEMPORARY_DESCRIPTION', 'https://en.wikipedia.org/wiki/Temporary_work'),
  ('COM_CAJOBBOARD_EMPLOYMENT_TYPE_CASUAL', 'COM_CAJOBBOARD_EMPLOYMENT_TYPE_CASUAL_DESCRIPTION', 'https://en.wikipedia.org/wiki/Casual_employment_(contract)'),
  ('COM_CAJOBBOARD_EMPLOYMENT_TYPE_INTERNSHIP', 'COM_CAJOBBOARD_EMPLOYMENT_TYPE_INTERNSHIP_DESCRIPTION', 'https://en.wikipedia.org/wiki/Internship');


/**
 * Initial occupational groups
 */
INSERT INTO `#__cajobboard_job_occupational_category_groups` (job_occupational_category_group_id, `group`, description, url) VALUES
  (1, 'COM_CAJOBBOARD_JOB_OCCUPATIONAL_GROUP_CONSTRUCTION', 'COM_CAJOBBOARD_JOB_OCCUPATIONAL_GROUP_CONSTRUCTION_DESCRIPTION', 'https://en.wikipedia.org/wiki/Construction'),
  (2, 'COM_CAJOBBOARD_JOB_OCCUPATIONAL_GROUP_FACILITIES', 'COM_CAJOBBOARD_JOB_OCCUPATIONAL_GROUP_FACILITIES_DESCRIPTION', 'https://en.wikipedia.org/wiki/Facility_management'),
  (3, 'COM_CAJOBBOARD_JOB_OCCUPATIONAL_GROUP_FINANCE', 'COM_CAJOBBOARD_JOB_OCCUPATIONAL_GROUP_FINANCE_DESCRIPTION', 'https://en.wikipedia.org/wiki/Accounting'),
  (4, 'COM_CAJOBBOARD_JOB_OCCUPATIONAL_GROUP_HR', 'COM_CAJOBBOARD_JOB_OCCUPATIONAL_GROUP_HR_DESCRIPTION', 'https://en.wikipedia.org/wiki/Human_resource_management'),
  (5, 'COM_CAJOBBOARD_JOB_OCCUPATIONAL_GROUP_IT', 'COM_CAJOBBOARD_JOB_OCCUPATIONAL_GROUP_IT_DESCRIPTION', 'https://en.wikipedia.org/wiki/Information_technology'),
  (6, 'COM_CAJOBBOARD_JOB_OCCUPATIONAL_GROUP_LEASING', 'COM_CAJOBBOARD_JOB_OCCUPATIONAL_GROUP_LEASING_DESCRIPTION', 'https://en.wikipedia.org/wiki/Letting_agent'),
  (7, 'COM_CAJOBBOARD_JOB_OCCUPATIONAL_GROUP_MARKETING', 'COM_CAJOBBOARD_JOB_OCCUPATIONAL_GROUP_MARKETING_DESCRIPTION', 'https://en.wikipedia.org/wiki/Marketing'),
  (8, 'COM_CAJOBBOARD_JOB_OCCUPATIONAL_GROUP_OFFICE', 'COM_CAJOBBOARD_JOB_OCCUPATIONAL_GROUP_OFFICE_DESCRIPTION', 'https://en.wikipedia.org/wiki/Office_administration'),
  (9, 'COM_CAJOBBOARD_JOB_OCCUPATIONAL_GROUP_MGMT', 'COM_CAJOBBOARD_JOB_OCCUPATIONAL_GROUP_MGMT_DESCRIPTION', 'https://en.wikipedia.org/wiki/Management');

/**
 * Initial occupational categories
 */
INSERT INTO `#__cajobboard_job_occupational_categories` (`code`, ordering, `group`, title) VALUES
  ('11-1021.00', 1, 9, 'COM_CAJOBBOARD_GENERAL_AND_OPERATIONS_MANAGERS'),
  ('11-9141.00', 2, 9, 'COM_CAJOBBOARD_PROPERTY_AND_COMMUNITY_MANAGERS'),
  ('11-9199.01', 3, 9, 'COM_CAJOBBOARD_REGULATORY_AFFAIRS_MANAGERS'),
  ('11-9199.00', 4, 9, 'COM_CAJOBBOARD_OTHER_MANAGERS'),
  ('11-2022.00', 1, 6, 'COM_CAJOBBOARD_LEASING_AND_SALES_MANAGERS_AND_EXECUTIVES'),
  ('41-3099.00', 2, 6, 'COM_CAJOBBOARD_LEASING_AND_SALES_AGENTS'),
  ('11-2021.00', 1, 7, 'COM_CAJOBBOARD_MARKETING_MANAGERS_AND_EXECUTIVES'),
  ('13-1161.00', 2, 7, 'COM_CAJOBBOARD_MARKETING_SPECIALISTS'),
  ('11-3031.00', 1, 3, 'COM_CAJOBBOARD_FINANCIAL_MANAGERS_AND_EXECUTIVES'),
  ('13-2011.01', 2, 3, 'COM_CAJOBBOARD_ACCOUNTANTS'),
  ('43-3031.00', 3, 3, 'COM_CAJOBBOARD_BOOKKEEPERS_AND_ACCOUNTING_CLERKS'),
  ('11-3021.00', 1, 5, 'COM_CAJOBBOARD_INFORMATION_TECHNOLOGY_MANAGERS_AND_EXECUTIVES'),
  ('15-1131.00', 2, 5, 'COM_CAJOBBOARD_COMPUTER_PROGRAMMERS'),
  ('15-1199.03', 3, 5, 'COM_CAJOBBOARD_WEB_ADMINISTRATORS'),
  ('11-3121.00', 1, 4, 'COM_CAJOBBOARD_HUMAN_RESOURCES_MANAGERS_AND_EXECUTIVES'),
  ('11-3131.00', 2, 4, 'COM_CAJOBBOARD_TRAINING_AND_DEVELOPMENT_MANAGERS'),
  ('13-1071.00', 3, 4, 'COM_CAJOBBOARD_HUMAN_RESOURCES_SPECIALISTS'),
  ('11-3011.00', 1, 8, 'COM_CAJOBBOARD_OFFICE_MANAGERS_AND_EXECUTIVES'),
  ('43-6014.00', 2, 8, 'COM_CAJOBBOARD_SECRETARIES_AND_ADMINISTRATIVE_ASSISTANTS'),
  ('43-4171.00', 3, 8, 'COM_CAJOBBOARD_RECEPTIONISTS'),
  ('43-4051.00', 4, 8, 'COM_CAJOBBOARD_CUSTOMER_SERVICE_REPRESENTATIVES'),
  ('43-4071.00', 5, 8, 'COM_CAJOBBOARD_FILE_CLERKS'),
  ('13-1041.00', 6, 8, 'COM_CAJOBBOARD_COMPLIANCE_OFFICERS'),
  ('13-1041.03', 7, 8, 'COM_CAJOBBOARD_EQUAL_OPPORTUNITY_REPRESENTATIVES_AND_OFFICERS'),
  ('37-1011.00', 1, 2, 'COM_CAJOBBOARD_HOUSEKEEPING_AND_JANITORIAL_SUPERVISORS'),
  ('37-2011.00', 2, 2, 'COM_CAJOBBOARD_JANITORS_AND_CLEANERS'),
  ('37-1012.00', 3, 2, 'COM_CAJOBBOARD_LANDSCAPING_LAWN_SERVICE_AND_GROUNDSKEEPING_SUPERVISORS'),
  ('37-3011.00', 4, 2, 'COM_CAJOBBOARD_LANDSCAPING_AND_GROUNDSKEEPING_WORKERS'),
  ('49-1011.00', 5, 2, 'COM_CAJOBBOARD_MAINTENANCE_SUPERVISORS'),
  ('49-9071.00', 6, 2, 'COM_CAJOBBOARD_MAINTENANCE_WORKERS'),
  ('11-9199.07', 7, 2, 'COM_CAJOBBOARD_SECURITY_MANAGERS'),
  ('33-9032.00', 8, 2, 'COM_CAJOBBOARD_SECURITY_GUARDS'),
  ('37-2021.00', 9, 2, 'COM_CAJOBBOARD_PEST_CONTROL_WORKERS'),
  ('11-9021.00', 1, 1, 'COM_CAJOBBOARD_PROJECT_AND_CONSTRUCTION_MANAGERS'),
  ('47-1011.00', 2, 1, 'COM_CAJOBBOARD_CONSTRUCTION_SUPERVISORS'),
  ('47-4099.00', 3, 1, 'COM_CAJOBBOARD_CONSTRUCTION_WORKERS_GENERAL'),
  ('47-2021.00', 4, 1, 'COM_CAJOBBOARD_BRICKMASONS_AND_BLOCKMASONS'),
  ('47-2031.00', 5, 1, 'COM_CAJOBBOARD_CARPENTERS'),
  ('47-2041.00', 6, 1, 'COM_CAJOBBOARD_CARPET_INSTALLERS'),
  ('47-2061.00', 7, 1, 'COM_CAJOBBOARD_CONSTRUCTION_LABORERS'),
  ('47-2081.00', 8, 1, 'COM_CAJOBBOARD_DRYWALL_AND_CEILING_TILE_INSTALLERS'),
  ('47-2111.00', 9, 1, 'COM_CAJOBBOARD_ELECTRICIANS'),
  ('47-2141.00', 10, 1, 'COM_CAJOBBOARD_PAINTERS_CONSTRUCTION_AND_MAINTENANCE'),
  ('47-2152.02', 11, 1, 'COM_CAJOBBOARD_PLUMBERS'),
  ('47-2181.00', 12, 1, 'COM_CAJOBBOARD_ROOFERS');

/*
 * Create content type for this table, mapping fields to the UCM standard fields for history feature
 */
INSERT INTO `#__content_types` (`type_id`, `type_title`, `type_alias`, `table`, `rules`, `field_mappings`, `router`, `content_history_options`)
VALUES(
  null, /* type_id */
  'Example Category', /* type_title */
  'com_cajobboard.category', /* type_alias */
  '{
    "common":{
      "dbtable":"#__ucm_content",
      "key":"ucm_id",
      "type":"Corecontent",
      "prefix":"JTable",
      "config":"array()"
    },
    "special":{
        "dbtable":"#__categories",
        "key":"id",
        "type":"Category",
        "prefix":"JTable",
        "config":"array()"
    }
  }', /* table */
  '', /* rules */
  '{
    "common":{
        "core_content_item_id":"id",
        "core_title":"title",
        "core_state":"published",
        "core_alias":"alias",
        "core_created_time":"created_time",
        "core_modified_time":"modified_time",
        "core_body":"description",
        "core_hits":"hits",
        "core_publish_up":"publish_up",
        "core_publish_down":"publish_down",
        "core_access":"access",
        "core_params":"params",
        "core_featured":"featured",
        "core_metadata":"metadata",
        "core_metakey":"metakey",
        "core_metadesc":"metadesc",
        "core_language":"language",
        "core_images":"null",
        "core_urls":"null",
        "core_version":"version",
        "core_ordering":"null",
        "core_catid":"cat_id",
        "core_xreference":"xreference",
        "asset_id":"asset_id"
    },
    "special":{
        "parent_id":"parent_id",
        "lft":"lft",
        "rgt":"rgt",
        "level":"level",
        "path":"path",
        "extension":"extension",
        "note":"note"
    }
  }', /* field_mappings */
  'CajobboardHelperRoute::getCategoryRoute', /* router */
  '{
    "formFile":"administrator\\/components\\/com_categories\\/models\\/forms\\/category.xml",
    "hideFields":[
        "asset_id",
        "checked_out",
        "checked_out_time",
        "version",
        "lft",
        "rgt",
        "level",
        "path",
        "extension"
    ],
  }' /* content_history_options */
);

/*
 *
 */
INSERT INTO `#__content_types` (`type_id`, `type_title`, `type_alias`, `table`, `rules`, `field_mappings`, `router`, `content_history_options`)
VALUES(
  null, /* type_id */
  'Cajobboards', /* type_title */
  'com_cajobboard.subscription', /* type_alias */
  '{
    "special":{
        "dbtable":"#__cajobboard_example",
        "key":"id",
        "type":"Example",
        "prefix":"CajobboardTable"
    }
  }', /* table */
  '', '', '', ''
);

/*
 * Set up pop-up fields for history comparison screens in admin UI
 */
INSERT INTO `#__content_types` (`type_id`, `type_title`, `type_alias`, `table`, `rules`, `field_mappings`, `router`, `content_history_options`)
VALUES(
  null,
  'Example',
  'com_cajobboard.example',
  '{
   "special":{
      "dbtable":"#__cajobboard_example",
      "key":"id",
      "type":"Examplen",
      "prefix":"CajobboardTable"
   }
  }',
  '', '', '',
  '{
    "formFile":"administrator\\/components\\/com_joomprosubs\\/models\\/forms\\/example.xml",
    "hideFields":[
        "checked_out",
        "checked_out_time",
        "params",
        "language"
    ],
    "ignoreChanges":[
        "modified_by",
        "modified",
        "checked_out",
        "checked_out_time"
    ],
    "convertToInt":[
        "publish_up",
        "publish_down"
    ],
    "displayLookup":[
        {
          "sourceColumn":"catid",
          "targetTable":"#__categories",
          "targetColumn":"id",
          "displayColumn":"title"
        },
        {
          "sourceColumn":"group_id",
          "targetTable":"#__usergroups",
          "targetColumn":"id",
          "displayColumn":"title"
        },
        {
          "sourceColumn":"created_by",
          "targetTable":"#__users",
          "targetColumn":"id",
          "displayColumn":"name"
        },
        {
          "sourceColumn":"access",
          "targetTable":"#__viewlevels",
          "targetColumn":"id",
          "displayColumn":"title"
        },
        {
          "sourceColumn":"modified_by",
          "targetTable":"#__users",
          "targetColumn":"id",
          "displayColumn":"name"
        }
    ]
  }'
);