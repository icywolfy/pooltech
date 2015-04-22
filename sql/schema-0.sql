CREATE TABLE `auditlog` (
  `ts` VARCHAR(16) COMMENT 'ISO DateTime',
  `module` VARCHAR(25) COMMENT 'Reporting System',
  `desc` VARCHAR(200) COMMENT 'Short Description',
  `details` TEXT COMMENT 'JSON Details'
) DEFAULT CHARACTER SET utf8;
CREATE INDEX `auditlog_ts` ON `auditlog` (ts ASC);


CREATE TABLE customer_data (
  `cid` INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `email` VARCHAR(64),
  `name` VARCHAR(128),
  `address_1` VARCHAR(128),
  `address_2` VARCHAR(128),
  `city` VARCHAR(32),
  `state` VARCHAR(6),
  `zip` VARCHAR(11),
  `phone` VARCHAR(24),
  `promo` VARCHAR(10) COMMENT 'Promotion Code',
  `product` VARCHAR(12) COMMENT 'Product Ordered',
  `stripe_id` VARCHAR(64) COMMENT 'Stripe Customer ID',
)
DEFAULT CHARACTER SET utf8;
CREATE INDEX `cd_lookup` ON `customer_data` (`email` ASC);

CREATE TABLE `transaction_data` (
  `tid` INTEGER NOT NULL  PRIMARY KEY AUTO_INCREMENT,
  `cid` INTEGER NOT NULL COMMENT 'Associated Customer DB ID',
  `ts` VARCHAR(16) COMMENT 'ISO DateTime',
  `email` VARCHAR(64) COMMENT 'Denormalized User Email',
  `stripe_id` VARCHAR(64) COMMENT 'Stripe Customer ID',
  `stripe_txn` VARCHAR(128) COMMENT 'Stripe Transaction ID',
  `amount` INTEGER COMMENT 'Amount charged in cents',
  `currency` VARCHAR(3) COMMENT 'ISO Currency Code',
  `status` VARCHAR(10) COMMENT 'Logical Completion Status',
  `detail` TEXT COMMENT 'JSON Transaction Details'
) DEFAULT CHARACTER SET utf8;
CREATE INDEX `td_sid` ON `transaction_data` (`stripe_id` ASC, `ts` DESC);


CREATE TABLE
