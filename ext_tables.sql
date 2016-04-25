#
# Table structure for table 'tx_koningmailchimpsignup_domain_model_subscriberlist'
#
CREATE TABLE tx_koningmailchimpsignup_domain_model_subscriberlist (
    uid int(11) NOT NULL auto_increment,
    pid int(11) DEFAULT '0',
    tstamp int(11) DEFAULT '0' NOT NULL,
    crdate int(11) DEFAULT '0' NOT NULL,
    cruser_id int(11) DEFAULT '0' NOT NULL,
    editlock tinyint(4) DEFAULT '0' NOT NULL,

    identifier varchar(255) DEFAULT '',
    name varchar(255) DEFAULT '',
    subscribers int(11) DEFAULT '0' NOT NULL,

    PRIMARY KEY (uid)
);

#
# Table structure for table 'tx_koningmailchimpsignup_domain_model_subscriber'
#
CREATE TABLE tx_koningmailchimpsignup_domain_model_subscriber (
    uid int(11) NOT NULL auto_increment,
    pid int(11) DEFAULT '0',
    tstamp int(11) DEFAULT '0' NOT NULL,
    crdate int(11) DEFAULT '0' NOT NULL,
    cruser_id int(11) DEFAULT '0' NOT NULL,
    editlock tinyint(4) DEFAULT '0' NOT NULL,

    email varchar(255) DEFAULT '',
    list int(11) DEFAULT '0' NOT NULL,

    PRIMARY KEY (uid)
);
