#
# Table structure for table 'mailchimp_audience'
#
CREATE TABLE mailchimp_audience (
    identifier varchar(255) DEFAULT '' NOT NULL,
    web_identifier int(11) DEFAULT '0' NOT NULL,
    name varchar(255) DEFAULT '' NOT NULL
);
