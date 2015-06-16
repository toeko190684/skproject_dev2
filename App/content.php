<?php


/*============================================================================================================================
ini modul master 
=============================================================================================================================*/
if ($_GET['r']=='home'){
	if($_SESSION[grade_id]=='*'){
		include "modul/mod_home/home.php";
	}else{
		include "modul/mod_home/user_home.php";
	}
}elseif (($_GET['r'])=='promo'){
	include "modul/mod_promo/promo.php";
}elseif (($_GET['r'])=='class'){
	include "modul/mod_class/class.php";
}elseif (($_GET['r'])=='subdepartemen'){
	include "modul/mod_subdepartemen/subdepartemen.php";
}elseif (($_GET['r'])=='masterbudget'){
    include "modul/mod_masterbudget/masterbudget.php";
}elseif (($_GET['r'])=='approvalbudget'){
    include "modul/mod_approvalbudget/approvalbudget.php";
}elseif (($_GET['r'])=='master_department'){
	include "modul/mod_master_department/master_department.php";
}elseif (($_GET['r'])=='master_divisi'){
	include "modul/mod_divisi/master_divisi.php";
}elseif (($_GET['r'])=='master_grade'){
	include "modul/mod_grade/master_grade.php";
}elseif (($_GET['r'])=='sec_app'){
	include "modul/mod_app/app.php";
}elseif (($_GET['r'])=='sec_app_module'){
	include "modul/mod_modul/modul.php";
}elseif (($_GET['r'])=='sec_users'){
	include "modul/mod_users/users.php";
}elseif (($_GET['r'])=='sec_rule'){
	include "modul/mod_rule/sec_rule.php";
}elseif (($_GET['r'])=='periode'){
	include "modul/mod_periode/periode.php";
}elseif (($_GET['r'])=='numbersetup'){
	include "modul/mod_numbersetup/numbersetup.php";
}elseif (($_GET['r'])=='emailnotification'){
	include "modul/mod_emailnotification/emailnotification.php";
}elseif (($_GET['r'])=='masterreport'){
	include "modul/mod_masterreport/masterreport.php";
}elseif (($_GET['r'])=='program'){
	include "modul/mod_program/program.php";
}elseif (($_GET['r'])=='account'){
	include "modul/mod_account/account.php";
}elseif (($_GET['r'])=='vendor'){
	include "modul/mod_vendor/vendor.php";
}elseif (($_GET['r'])=='approval_limit'){
	include "modul/mod_approvallimit/approvallimit.php";
}

//BUDGETTING.
elseif (($_GET['r'])=='approval'){
	include "modul/mod_approval/approval.php";
}elseif (($_GET['r'])=='nasional'){
	include "modul/mod_nasional/nasional.php";
}elseif (($_GET['r'])=='area'){
	include "modul/mod_area/area.php";
}elseif (($_GET['r'])=='regional'){
	include "modul/mod_regional/regional.php";
}elseif (($_GET['r'])=='distributor'){
	include "modul/mod_distributor/distributor.php";
}elseif (($_GET['r'])=='groupoutlet'){
	include "modul/mod_groupoutlet/groupoutlet.php";
}elseif (($_GET['r'])=='promorequest'){
	include "modul/mod_promorequest/promorequest.php";
}elseif (($_GET['r'])=='promoapproval'){
	include "modul/mod_promoapproval/promoapproval.php";
}elseif (($_GET['r'])=='recoattachment'){
	include "modul/mod_recoattachment/recoattachment.php";
}elseif (($_GET['r'])=='recoclosing'){
	include "modul/mod_recoclosing/recoclosing.php";
}


//ASSET MANAGEMENT
elseif (($_GET['r'])=='company'){
	include "modul/mod_company/company.php";
}elseif (($_GET['r'])=='cabang'){
	include "modul/mod_cabang/cabang.php";
}elseif (($_GET['r'])=='kategoriaset'){
	include "modul/mod_kategoriaset/kategoriaset.php";
}elseif (($_GET['r'])=='lokasiasset'){
	include "modul/mod_lokasiasset/lokasiasset.php";
}elseif (($_GET['r'])=='unitasset'){
	include "modul/mod_unitasset/unitasset.php";
}elseif (($_GET['r'])=='asset'){
	include "modul/mod_asset/asset.php";
}elseif (($_GET['r'])=='transaksiasset'){
	include "modul/mod_transaksiasset/transaksiasset.php";
}
//SELLING IN
elseif (($_GET['r'])=='sobydivisi'){
	include "modul/mod_sobydivisi/sobydivisi.php";
}elseif (($_GET['r'])=='sobybrand'){
	include "modul/mod_sobybrand/sobybrand.php";
}elseif (($_GET['r'])=='sobysubbrand'){
	include "modul/mod_sobysubbrand/sobysubbrand.php";
}elseif (($_GET['r'])=='sobyproduct'){
	include "modul/mod_sobyproduct/sobyproduct.php";
}elseif (($_GET['r'])=='sotarget'){
	include "modul/mod_sotarget/sotarget.php";
}
//CLAIM
elseif (($_GET['r'])=='konversicoa'){
	include "modul/mod_konversicoa/konversicoa.php";
}elseif (($_GET['r'])=='claimrequest'){
	include "modul/mod_claimrequest/claimrequest.php";
}elseif (($_GET['r'])=='claimapproval'){
	include "modul/mod_claimapproval/claimapproval.php";
}
//REPORT
elseif (($_GET['r'])=='budgetreport'){
	include "modul/mod_budgetreport/budgetreport.php";
}elseif (($_GET['r'])=='claimreport'){
	include "modul/mod_claimreport/claimreport.php";
}elseif (($_GET['r'])=='recoreport'){
	include "modul/mod_recoreport/recoreport.php";
}elseif (($_GET['r'])=='suratprogram'){
	include "modul/mod_suratprogram/suratprogram.php";
}elseif (($_GET['r'])=='acc_claim_report'){
	include "modul/mod_acc_claim_report/acc_claim_report.php";
}elseif (($_GET['r'])=='acc_reco_report'){
	include "modul/mod_acc_reco_report/acc_reco_report.php";
}elseif (($_GET['r'])=='acc_budget_report'){
	include "modul/mod_acc_budget_report/acc_budget_report.php";
}elseif (($_GET['r'])=='acc_reco_claim'){
	include "modul/mod_acc_reco_claim/acc_reco_claim.php";
}elseif (($_GET['r'])=='mkt_reco_report'){
	include "modul/mod_mkt_reco_report/mkt_reco_report.php";
}elseif (($_GET['r'])=='bod_promotion_report'){
	include "modul/mod_bod_promotion_report/bod_promotion_report.php";
}elseif (($_GET['r'])=='reco_closing_report'){
	include "modul/mod_reco_closing_report/reco_closing_report.php";
}else{
    echo "<script>$.messager.alert('SKProject','Maaf $_SESSION[user_id], Modul belum ada ..! ','info');</script>";
}
?>











