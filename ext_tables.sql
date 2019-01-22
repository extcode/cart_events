#
# Table structure for table 'tx_cartevents_domain_model_event'
#
CREATE TABLE tx_cartevents_domain_model_event (
    uid int(11) NOT NULL auto_increment,
    pid int(11) DEFAULT '0' NOT NULL,

    event_dates int(11) unsigned DEFAULT '0' NOT NULL,

    sku varchar(255) DEFAULT '' NOT NULL,
    title varchar(255) DEFAULT '' NOT NULL,
    path_segment varchar(2048),
    teaser text NOT NULL,
    description text NOT NULL,
    meta_description text NOT NULL,
    audience text NOT NULL,

    images varchar(255) DEFAULT '' NOT NULL,
    files varchar(255) DEFAULT '' NOT NULL,

    related_events int(11) DEFAULT '0' NOT NULL,
    related_events_from int(11) DEFAULT '0' NOT NULL,

    category int(11) unsigned DEFAULT '0' NOT NULL,
    categories int(11) unsigned DEFAULT '0' NOT NULL,
    tags int(11) DEFAULT '0' NOT NULL,

    tax_class_id int(11) unsigned DEFAULT '1' NOT NULL,

    form_definition varchar(255) DEFAULT '' NOT NULL,

    sorting int(11) DEFAULT '0' NOT NULL,

    tstamp int(11) unsigned DEFAULT '0' NOT NULL,
    crdate int(11) unsigned DEFAULT '0' NOT NULL,
    cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
    deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
    hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
    starttime int(11) unsigned DEFAULT '0' NOT NULL,
    endtime int(11) unsigned DEFAULT '0' NOT NULL,

    t3ver_oid int(11) DEFAULT '0' NOT NULL,
    t3ver_id int(11) DEFAULT '0' NOT NULL,
    t3ver_wsid int(11) DEFAULT '0' NOT NULL,
    t3ver_label varchar(255) DEFAULT '' NOT NULL,
    t3ver_state tinyint(4) DEFAULT '0' NOT NULL,
    t3ver_stage int(11) DEFAULT '0' NOT NULL,
    t3ver_count int(11) DEFAULT '0' NOT NULL,
    t3ver_tstamp int(11) DEFAULT '0' NOT NULL,
    t3ver_move_id int(11) DEFAULT '0' NOT NULL,

    sys_language_uid int(11) DEFAULT '0' NOT NULL,
    l10n_parent int(11) DEFAULT '0' NOT NULL,
    l10n_diffsource mediumblob,

    PRIMARY KEY (uid),
    KEY parent (pid),
    KEY t3ver_oid (t3ver_oid,t3ver_wsid),
    KEY language (l10n_parent,sys_language_uid)
);

#
# Table structure for table 'tx_cartevents_domain_model_eventdate'
#
CREATE TABLE tx_cartevents_domain_model_eventdate (
    uid int(11) NOT NULL auto_increment,
    pid int(11) DEFAULT '0' NOT NULL,

    event int(11) unsigned DEFAULT '0' NOT NULL,
    calendar_entries int(11) unsigned DEFAULT '0' NOT NULL,

    sku varchar(255) DEFAULT '' NOT NULL,
    title varchar(255) DEFAULT '' NOT NULL,
    location text NOT NULL,
    lecturer text NOT NULL,

    images varchar(255) DEFAULT '' NOT NULL,
    files varchar(255) DEFAULT '' NOT NULL,

    begin int(11) unsigned DEFAULT '0' NOT NULL,
    end int(11) unsigned DEFAULT '0' NOT NULL,
    note text NOT NULL,

    bookable tinyint(4) unsigned DEFAULT '0' NOT NULL,

    bookable_until int(11) unsigned DEFAULT '0' NOT NULL,

    price double(11,2) DEFAULT '0.00' NOT NULL,
    special_prices int(11) unsigned DEFAULT '0' NOT NULL,

    handle_seats tinyint(4) unsigned DEFAULT '0' NOT NULL,
    seats_number int(11) unsigned DEFAULT '0' NOT NULL,
    seats_taken int(11) unsigned DEFAULT '0' NOT NULL,

    sorting int(11) DEFAULT '0' NOT NULL,

    tstamp int(11) unsigned DEFAULT '0' NOT NULL,
    crdate int(11) unsigned DEFAULT '0' NOT NULL,
    cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
    deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
    hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
    starttime int(11) unsigned DEFAULT '0' NOT NULL,
    endtime int(11) unsigned DEFAULT '0' NOT NULL,

    t3ver_oid int(11) DEFAULT '0' NOT NULL,
    t3ver_id int(11) DEFAULT '0' NOT NULL,
    t3ver_wsid int(11) DEFAULT '0' NOT NULL,
    t3ver_label varchar(255) DEFAULT '' NOT NULL,
    t3ver_state tinyint(4) DEFAULT '0' NOT NULL,
    t3ver_stage int(11) DEFAULT '0' NOT NULL,
    t3ver_count int(11) DEFAULT '0' NOT NULL,
    t3ver_tstamp int(11) DEFAULT '0' NOT NULL,
    t3ver_move_id int(11) DEFAULT '0' NOT NULL,

    sys_language_uid int(11) DEFAULT '0' NOT NULL,
    l10n_parent int(11) DEFAULT '0' NOT NULL,
    l10n_diffsource mediumblob,

    PRIMARY KEY (uid),
    KEY parent (pid),
    KEY t3ver_oid (t3ver_oid,t3ver_wsid),
    KEY language (l10n_parent,sys_language_uid)
);

#
# Table structure for table 'tx_cartevents_domain_model_calendarentry'
#
CREATE TABLE tx_cartevents_domain_model_calendarentry (
    uid int(11) NOT NULL auto_increment,
    pid int(11) DEFAULT '0' NOT NULL,

    event_date int(11) unsigned DEFAULT '0' NOT NULL,

    begin int(11) unsigned DEFAULT '0' NOT NULL,
    end int(11) unsigned DEFAULT '0' NOT NULL,
    note text NOT NULL,

    tstamp int(11) unsigned DEFAULT '0' NOT NULL,
    crdate int(11) unsigned DEFAULT '0' NOT NULL,
    cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
    deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
    hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
    starttime int(11) unsigned DEFAULT '0' NOT NULL,
    endtime int(11) unsigned DEFAULT '0' NOT NULL,

    PRIMARY KEY (uid),
    KEY parent (pid)
);

#
# Table structure for table 'tx_cartevents_domain_model_specialprice'
#
CREATE TABLE tx_cartevents_domain_model_specialprice (
    uid int(11) NOT NULL auto_increment,
    pid int(11) DEFAULT '0' NOT NULL,

    event_date int(11) unsigned DEFAULT '0' NOT NULL,

    title varchar(255) DEFAULT '' NOT NULL,

    frontend_user_group int(11) unsigned DEFAULT '0' NOT NULL,

    price double(11,2) DEFAULT '0.00' NOT NULL,

    tstamp int(11) unsigned DEFAULT '0' NOT NULL,
    crdate int(11) unsigned DEFAULT '0' NOT NULL,
    cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
    deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
    hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
    starttime int(11) unsigned DEFAULT '0' NOT NULL,
    endtime int(11) unsigned DEFAULT '0' NOT NULL,

    t3ver_oid int(11) DEFAULT '0' NOT NULL,
    t3ver_id int(11) DEFAULT '0' NOT NULL,
    t3ver_wsid int(11) DEFAULT '0' NOT NULL,
    t3ver_label varchar(255) DEFAULT '' NOT NULL,
    t3ver_state tinyint(4) DEFAULT '0' NOT NULL,
    t3ver_stage int(11) DEFAULT '0' NOT NULL,
    t3ver_count int(11) DEFAULT '0' NOT NULL,
    t3ver_tstamp int(11) DEFAULT '0' NOT NULL,
    t3ver_move_id int(11) DEFAULT '0' NOT NULL,

    sys_language_uid int(11) DEFAULT '0' NOT NULL,
    l10n_parent int(11) DEFAULT '0' NOT NULL,
    l10n_diffsource mediumblob,

    PRIMARY KEY (uid),
    KEY parent (pid),
    KEY t3ver_oid (t3ver_oid,t3ver_wsid),
    KEY language (l10n_parent,sys_language_uid)
);

#
# Table structure for table 'tx_cartevents_domain_model_event_event_related_mm'
#
CREATE TABLE tx_cartevents_domain_model_event_event_related_mm (
    uid_local int(11) DEFAULT '0' NOT NULL,
    uid_foreign int(11) DEFAULT '0' NOT NULL,
    sorting int(11) DEFAULT '0' NOT NULL,
    sorting_foreign int(11) DEFAULT '0' NOT NULL,
    KEY uid_local (uid_local),
    KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_cartevents_domain_model_event_tag_mm'
#
CREATE TABLE tx_cartevents_domain_model_event_tag_mm (
    uid_local int(11) DEFAULT '0' NOT NULL,
    uid_foreign int(11) DEFAULT '0' NOT NULL,
    sorting int(11) DEFAULT '0' NOT NULL,
    sorting_foreign int(11) DEFAULT '0' NOT NULL,
    KEY uid_local (uid_local),
    KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'pages'
#
CREATE TABLE pages (
    cart_events_event int(11) unsigned DEFAULT '0',
);

#
# Extend table structure of table 'sys_category'
#
CREATE TABLE sys_category (
    cart_event_list_pid int(11) unsigned DEFAULT '0' NOT NULL,
    cart_event_show_pid int(11) unsigned DEFAULT '0' NOT NULL
);
