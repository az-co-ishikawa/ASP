<?php

/*****************************************************************
 ** 汎用プログラム（regist.php / search.php / info.php）用 定義 **
 *****************************************************************/
/**********          テーブル定義          **********/


/**********          adminの定義          **********/

	$EDIT_TYPE                            = 'admin';
	$TABLE_NAME[]                         = $EDIT_TYPE;
	$THIS_TABLE_IS_USERDATA[ $EDIT_TYPE ] = true;
	$THIS_TABLE_IS_NOHTML[ $EDIT_TYPE ]   = false;
	$LOGIN_KEY_COLUM[ $EDIT_TYPE ]        = 'mail';
	$LOGIN_PASSWD_COLUM[ $EDIT_TYPE ]     = 'pass';
	$LOGIN_PASSWD_COLUM2[ $EDIT_TYPE ]    = 'pass_confirm';
	$LST[ $EDIT_TYPE ]                    = './lst/admin.csv';
	$TDB[ $EDIT_TYPE ]                    = './tdb/admin.csv';
	$ID_HEADER[ $EDIT_TYPE ]              = 'ADMIN';
	$ID_LENGTH[ $EDIT_TYPE ]              = 8;


/**********          nUserの定義          **********/

	$EDIT_TYPE                            = 'nUser';
	$TABLE_NAME[]                         = $EDIT_TYPE;
	$THIS_TABLE_IS_USERDATA[ $EDIT_TYPE ] = true;
	$THIS_TABLE_IS_NOHTML[ $EDIT_TYPE ]   = false;
	$LOGIN_KEY_COLUM[ $EDIT_TYPE ]        = 'mail';
	$LOGIN_PASSWD_COLUM[ $EDIT_TYPE ]     = 'pass';
	$LOGIN_PASSWD_COLUM2[ $EDIT_TYPE ]    = 'pass_confirm';
	$LST[ $EDIT_TYPE ]                    = './lst/nuser.csv';
	$TDB[ $EDIT_TYPE ]                    = './tdb/nuser.csv';
	$ID_HEADER[ $EDIT_TYPE ]              = 'N';
	$ID_LENGTH[ $EDIT_TYPE ]              = 8;
	$THIS_TABLE_IS_QUICK[ $EDIT_TYPE ] 	  = true;

/**********          adwaresの定義          **********/

	$EDIT_TYPE                            = 'adwares';
	$TABLE_NAME[]                         = $EDIT_TYPE;
	$THIS_TABLE_IS_USERDATA[ $EDIT_TYPE ] = false;
	$THIS_TABLE_IS_NOHTML[ $EDIT_TYPE ]   = false;
	$LOGIN_KEY_COLUM[ $EDIT_TYPE ]        = null;
	$LOGIN_PASSWD_COLUM[ $EDIT_TYPE ]     = null;
	$LOGIN_PASSWD_COLUM2[ $EDIT_TYPE ]    = null;
	$LST[ $EDIT_TYPE ]                    = './lst/adwares.csv';
	$TDB[ $EDIT_TYPE ]                    = './tdb/adwares.csv';
	$ID_HEADER[ $EDIT_TYPE ]              = 'A';
	$ID_LENGTH[ $EDIT_TYPE ]              = 8;


/**********          categoryの定義          **********/

	$EDIT_TYPE                            = 'category';
	$TABLE_NAME[]                         = $EDIT_TYPE;
	$THIS_TABLE_IS_USERDATA[ $EDIT_TYPE ] = false;
	$THIS_TABLE_IS_NOHTML[ $EDIT_TYPE ]   = false;
	$LOGIN_KEY_COLUM[ $EDIT_TYPE ]        = null;
	$LOGIN_PASSWD_COLUM[ $EDIT_TYPE ]     = null;
	$LOGIN_PASSWD_COLUM2[ $EDIT_TYPE ]    = null;
	$LST[ $EDIT_TYPE ]                    = './lst/category.csv';
	$TDB[ $EDIT_TYPE ]                    = './tdb/category.csv';
	$ID_HEADER[ $EDIT_TYPE ]              = 'CT';
	$ID_LENGTH[ $EDIT_TYPE ]              = 8;


/**********          accessの定義          **********/

	$EDIT_TYPE                            = 'access';
	$TABLE_NAME[]                         = $EDIT_TYPE;
	$THIS_TABLE_IS_USERDATA[ $EDIT_TYPE ] = false;
	$THIS_TABLE_IS_NOHTML[ $EDIT_TYPE ]   = false;
	$LOGIN_KEY_COLUM[ $EDIT_TYPE ]        = null;
	$LOGIN_PASSWD_COLUM[ $EDIT_TYPE ]     = null;
	$LOGIN_PASSWD_COLUM2[ $EDIT_TYPE ]    = null;
	$LST[ $EDIT_TYPE ]                    = './lst/access.csv';
	$TDB[ $EDIT_TYPE ]                    = './tdb/access.csv';
	$ID_HEADER[ $EDIT_TYPE ]              = 'AC';
	$ID_LENGTH[ $EDIT_TYPE ]              = 8;


/**********          payの定義          **********/

	$EDIT_TYPE                            = 'pay';
	$TABLE_NAME[]                         = $EDIT_TYPE;
	$THIS_TABLE_IS_USERDATA[ $EDIT_TYPE ] = false;
	$THIS_TABLE_IS_NOHTML[ $EDIT_TYPE ]   = false;
	$LOGIN_KEY_COLUM[ $EDIT_TYPE ]        = null;
	$LOGIN_PASSWD_COLUM[ $EDIT_TYPE ]     = null;
	$LOGIN_PASSWD_COLUM2[ $EDIT_TYPE ]    = null;
	$LST[ $EDIT_TYPE ]                    = './lst/pay.csv';
	$TDB[ $EDIT_TYPE ]                    = './tdb/pay.csv';
	$ID_HEADER[ $EDIT_TYPE ]              = '';
	$ID_LENGTH[ $EDIT_TYPE ]              = 0;


/**********          click_payの定義          **********/

	$EDIT_TYPE                            = 'click_pay';
	$TABLE_NAME[]                         = $EDIT_TYPE;
	$THIS_TABLE_IS_USERDATA[ $EDIT_TYPE ] = false;
	$THIS_TABLE_IS_NOHTML[ $EDIT_TYPE ]   = false;
	$LOGIN_KEY_COLUM[ $EDIT_TYPE ]        = null;
	$LOGIN_PASSWD_COLUM[ $EDIT_TYPE ]     = null;
	$LOGIN_PASSWD_COLUM2[ $EDIT_TYPE ]    = null;
	$LST[ $EDIT_TYPE ]                    = './lst/click_pay.csv';
	$TDB[ $EDIT_TYPE ]                    = './tdb/click_pay.csv';
	$ID_HEADER[ $EDIT_TYPE ]              = '';
	$ID_LENGTH[ $EDIT_TYPE ]              = 32;
	

/**********          click_payの定義          **********/

	$EDIT_TYPE                            = 'continue_pay';
	$TABLE_NAME[]                         = $EDIT_TYPE;
	$THIS_TABLE_IS_USERDATA[ $EDIT_TYPE ] = false;
	$THIS_TABLE_IS_NOHTML[ $EDIT_TYPE ]   = false;
	$LOGIN_KEY_COLUM[ $EDIT_TYPE ]        = null;
	$LOGIN_PASSWD_COLUM[ $EDIT_TYPE ]     = null;
	$LOGIN_PASSWD_COLUM2[ $EDIT_TYPE ]    = null;
	$LST[ $EDIT_TYPE ]                    = './lst/continue_pay.csv';
	$TDB[ $EDIT_TYPE ]                    = './tdb/continue_pay.csv';
	$ID_HEADER[ $EDIT_TYPE ]              = '';
	$ID_LENGTH[ $EDIT_TYPE ]              = 32;
	

/**********          returnssの定義          **********/

	$EDIT_TYPE                            = 'returnss';
	$TABLE_NAME[]                         = $EDIT_TYPE;
	$THIS_TABLE_IS_USERDATA[ $EDIT_TYPE ] = false;
	$THIS_TABLE_IS_NOHTML[ $EDIT_TYPE ]   = false;
	$LOGIN_KEY_COLUM[ $EDIT_TYPE ]        = null;
	$LOGIN_PASSWD_COLUM[ $EDIT_TYPE ]     = null;
	$LOGIN_PASSWD_COLUM2[ $EDIT_TYPE ]    = null;
	$LST[ $EDIT_TYPE ]                    = './lst/returnss.csv';
	$TDB[ $EDIT_TYPE ]                    = './tdb/returnss.csv';
	$ID_HEADER[ $EDIT_TYPE ]              = 'R';
	$ID_LENGTH[ $EDIT_TYPE ]              = 8;


/**********          prefecturesの定義          **********/

	$EDIT_TYPE                            = 'prefectures';
	$TABLE_NAME[]                         = $EDIT_TYPE;
	$THIS_TABLE_IS_USERDATA[ $EDIT_TYPE ] = false;
	$THIS_TABLE_IS_NOHTML[ $EDIT_TYPE ]   = false;
	$LOGIN_KEY_COLUM[ $EDIT_TYPE ]        = null;
	$LOGIN_PASSWD_COLUM[ $EDIT_TYPE ]     = null;
	$LOGIN_PASSWD_COLUM2[ $EDIT_TYPE ]    = null;
	$LST[ $EDIT_TYPE ]                    = './lst/prefectures.csv';
	$TDB[ $EDIT_TYPE ]                    = './tdb/prefectures.csv';
	$ID_HEADER[ $EDIT_TYPE ]              = 'PF';
	$ID_LENGTH[ $EDIT_TYPE ]              = 4;


/**********          areaの定義          **********/

	$EDIT_TYPE                            = 'area';
	$TABLE_NAME[]                         = $EDIT_TYPE;
	$THIS_TABLE_IS_USERDATA[ $EDIT_TYPE ] = false;
	$THIS_TABLE_IS_NOHTML[ $EDIT_TYPE ]   = false;
	$LOGIN_KEY_COLUM[ $EDIT_TYPE ]        = null;
	$LOGIN_PASSWD_COLUM[ $EDIT_TYPE ]     = null;
	$LOGIN_PASSWD_COLUM2[ $EDIT_TYPE ]    = null;
	$LST[ $EDIT_TYPE ]                    = './lst/area.csv';
	$TDB[ $EDIT_TYPE ]                    = './tdb/area.csv';
	$ID_HEADER[ $EDIT_TYPE ]              = 'AREA';
	$ID_LENGTH[ $EDIT_TYPE ]              = 6;


/**********          salesの定義          **********/

	$EDIT_TYPE                            = 'sales';
	$TABLE_NAME[]                         = $EDIT_TYPE;
	$THIS_TABLE_IS_USERDATA[ $EDIT_TYPE ] = false;
	$THIS_TABLE_IS_NOHTML[ $EDIT_TYPE ]   = false;
	$LOGIN_KEY_COLUM[ $EDIT_TYPE ]        = null;
	$LOGIN_PASSWD_COLUM[ $EDIT_TYPE ]     = null;
	$LOGIN_PASSWD_COLUM2[ $EDIT_TYPE ]    = null;
	$LST[ $EDIT_TYPE ]                    = './lst/sales.csv';
	$TDB[ $EDIT_TYPE ]                    = './tdb/sales.csv';
	$ID_HEADER[ $EDIT_TYPE ]              = 'SA';
	$ID_LENGTH[ $EDIT_TYPE ]              = 4;


/**********          multimailの定義          **********/

	$EDIT_TYPE                            = 'multimail';
	$TABLE_NAME[]                         = $EDIT_TYPE;
	$THIS_TABLE_IS_USERDATA[ $EDIT_TYPE ] = false;
	$THIS_TABLE_IS_NOHTML[ $EDIT_TYPE ]   = false;
	$LOGIN_KEY_COLUM[ $EDIT_TYPE ]        = null;
	$LOGIN_PASSWD_COLUM[ $EDIT_TYPE ]     = null;
	$LOGIN_PASSWD_COLUM2[ $EDIT_TYPE ]    = null;
	$LST[ $EDIT_TYPE ]                    = './lst/multimail.csv';
	$TDB[ $EDIT_TYPE ]                    = './tdb/multimail.csv';
	$ID_HEADER[ $EDIT_TYPE ]              = 'MM';
	$ID_LENGTH[ $EDIT_TYPE ]              = 8;


/**********          templateの定義          **********/

	$EDIT_TYPE                            = 'template';
	$TABLE_NAME[]                         = $EDIT_TYPE;
	$THIS_TABLE_IS_USERDATA[ $EDIT_TYPE ] = false;
	$THIS_TABLE_IS_NOHTML[ $EDIT_TYPE ]   = false;
	$LOGIN_KEY_COLUM[ $EDIT_TYPE ]        = null;
	$LOGIN_PASSWD_COLUM[ $EDIT_TYPE ]     = null;
	$LOGIN_PASSWD_COLUM2[ $EDIT_TYPE ]    = null;
	$LST[ $EDIT_TYPE ]                    = './lst/template.csv';
	$TDB[ $EDIT_TYPE ]                    = './tdb/template.csv';
	$ID_HEADER[ $EDIT_TYPE ]              = 'T';
	$ID_LENGTH[ $EDIT_TYPE ]              = 5;


/**********          systemの定義          **********/

	$EDIT_TYPE                            = 'system';
	$TABLE_NAME[]                         = $EDIT_TYPE;
	$THIS_TABLE_IS_USERDATA[ $EDIT_TYPE ] = false;
	$THIS_TABLE_IS_NOHTML[ $EDIT_TYPE ]   = false;
	$LOGIN_KEY_COLUM[ $EDIT_TYPE ]        = null;
	$LOGIN_PASSWD_COLUM[ $EDIT_TYPE ]     = null;
	$LOGIN_PASSWD_COLUM2[ $EDIT_TYPE ]    = null;
	$LST[ $EDIT_TYPE ]                    = './lst/system.csv';
	$TDB[ $EDIT_TYPE ]                    = './tdb/system.csv';
	$ID_HEADER[ $EDIT_TYPE ]              = '';
	$ID_LENGTH[ $EDIT_TYPE ]              = 0;


/**********       invitationの定義       **********/

	$EDIT_TYPE                            = 'invitation';
	$TABLE_NAME[]                         = $EDIT_TYPE;
	$THIS_TABLE_IS_USERDATA[ $EDIT_TYPE ] = false;
	$THIS_TABLE_IS_NOHTML[ $EDIT_TYPE ]   = false;
	$LOGIN_KEY_COLUM[ $EDIT_TYPE ]        = null;
	$LOGIN_PASSWD_COLUM[ $EDIT_TYPE ]     = null;
	$LOGIN_PASSWD_COLUM2[ $EDIT_TYPE ]    = null;
	$LST[ $EDIT_TYPE ]                    = './lst/invitation.csv';
	$TDB[ $EDIT_TYPE ]                    = './tdb/invitation.csv';
	$ID_HEADER[ $EDIT_TYPE ]              = 'I';
	$ID_LENGTH[ $EDIT_TYPE ]              = 8;

/**********          pageの定義          **********/

	$EDIT_TYPE                            = 'page';
	$TABLE_NAME[]                         = $EDIT_TYPE;
	$THIS_TABLE_IS_USERDATA[ $EDIT_TYPE ] = false;
	$THIS_TABLE_IS_NOHTML[ $EDIT_TYPE ]   = false;
	$LOGIN_KEY_COLUM[ $EDIT_TYPE ]        = null;
	$LOGIN_PASSWD_COLUM[ $EDIT_TYPE ]     = null;
	$LOGIN_PASSWD_COLUM2[ $EDIT_TYPE ]    = null;
	$LST[ $EDIT_TYPE ]                    = './lst/page.csv';
	$TDB[ $EDIT_TYPE ]                    = './tdb/page.csv';
	$ID_HEADER[ $EDIT_TYPE ]              = 'P';
	$ID_LENGTH[ $EDIT_TYPE ]              = 6;


/**********          tierの定義          **********/

	$EDIT_TYPE                            = 'tier';
	$TABLE_NAME[]                         = $EDIT_TYPE;
	$THIS_TABLE_IS_USERDATA[ $EDIT_TYPE ] = false;
	$THIS_TABLE_IS_NOHTML[ $EDIT_TYPE ]   = false;
	$LOGIN_KEY_COLUM[ $EDIT_TYPE ]        = null;
	$LOGIN_PASSWD_COLUM[ $EDIT_TYPE ]     = null;
	$LOGIN_PASSWD_COLUM2[ $EDIT_TYPE ]    = null;
	$LST[ $EDIT_TYPE ]                    = './lst/tier.csv';
	$TDB[ $EDIT_TYPE ]                    = './tdb/tier.csv';
	$ID_HEADER[ $EDIT_TYPE ]              = '';
	$ID_LENGTH[ $EDIT_TYPE ]              = 33;


/**********          zenginkyoの定義          **********/

	$EDIT_TYPE                            = 'zenginkyo';
	$TABLE_NAME[]                         = $EDIT_TYPE;
	$THIS_TABLE_IS_USERDATA[ $EDIT_TYPE ] = false;
	$THIS_TABLE_IS_NOHTML[ $EDIT_TYPE ]   = false;
	$LOGIN_KEY_COLUM[ $EDIT_TYPE ]        = null;
	$LOGIN_PASSWD_COLUM[ $EDIT_TYPE ]     = null;
	$LOGIN_PASSWD_COLUM2[ $EDIT_TYPE ]    = null;
	$LST[ $EDIT_TYPE ]                    = './lst/zenginkyo.csv';
	$TDB[ $EDIT_TYPE ]                    = './tdb/zenginkyo.csv';
	$ID_HEADER[ $EDIT_TYPE ]              = '';
	$ID_LENGTH[ $EDIT_TYPE ]              = 5;


/**********          log_payの定義          **********/

	$EDIT_TYPE                            = 'log_pay';
	$TABLE_NAME[]                         = $EDIT_TYPE;
	$THIS_TABLE_IS_USERDATA[ $EDIT_TYPE ] = false;
	$THIS_TABLE_IS_NOHTML[ $EDIT_TYPE ]   = false;
	$LOGIN_KEY_COLUM[ $EDIT_TYPE ]        = null;
	$LOGIN_PASSWD_COLUM[ $EDIT_TYPE ]     = null;
	$LOGIN_PASSWD_COLUM2[ $EDIT_TYPE ]    = null;
	$LST[ $EDIT_TYPE ]                    = './lst/log_pay.csv';
	$TDB[ $EDIT_TYPE ]                    = './tdb/log_pay.csv';
	$ID_HEADER[ $EDIT_TYPE ]              = '';
	$ID_LENGTH[ $EDIT_TYPE ]              = 10;

	
