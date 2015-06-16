<?php
function target($tgl,$category){
	include "../../../configuration/connection_inc.php";
	$bln = date('m',strtotime($tgl));
	$thn = date('Y',strtotime($tgl));
	$x = mysql_query("SELECT a.value FROM sales_order_target a,sales_order_divisi b WHERE
					 a.divisi_id=b.divisi_id and  a.bulan=$bln and a.tahun=$thn and b.divisi_name='$category'");
	$rx = mysql_fetch_array($x);
	return $rx[value];
}

function basic_target($tgl,$category){
	include "../../../configuration/connection_inc.php";
	$bln = date('m',strtotime($tgl));
	$thn = date('Y',strtotime($tgl));
	$x = mysql_query("SELECT a.basic FROM sales_order_target a,sales_order_divisi b WHERE
					 a.divisi_id=b.divisi_id and  a.bulan=$bln and a.tahun=$thn and b.divisi_name='$category'");
	$rx = mysql_fetch_array($x);
	return $rx[basic];
}

/*function lastyear($tgl,$category){
	include "../../../configuration/connection_inc.php";
	$bln = date('m',strtotime($tgl));
	$thn = date('Y',strtotime($tgl))-1;
	$x = mysql_query("SELECT a.value FROM sales_order_target a,sales_order_divisi b WHERE
					 a.divisi_id=b.divisi_id and  a.bulan=$bln and a.tahun=$thn and b.divisi_name='$category'");
	$rx = mysql_fetch_array($x);
	return $rx[value];
}*/

function lastyear($tgl_awal,$tgl_akhir,$category){
    include "../../../configuration/connection_inc.php";
	$x = "select sum(a.quantity*b.sale_price*0.875*0.99) as total from kinosentrajit.dbo.do_item a,
		 sales_order_item b,sales_order c,kinosentrajit.dbo.do d, distributor e
		 where b.quotation_id=c.quotation_id and a.quotation_id=b.quotation_id and a.product_id=b.product_id and
         a.do_id=d.do_id and d.ok=1 and  c.distributor_id=e.distributor_id and e.distributor_group='LOKAL' and
		 substring(CONVERT(VARCHAR(10), c.quotation_date, 120),1,7) = substring('$tgl_awal',1,7) and a.product_id in
		(select product_id from product where category='$category')and a.quantity>0";
	$qx = odbc_exec($conn2,$x);
	$rx = odbc_fetch_array($qx);
	return $rx[total];
}

function so_divisi($tgl_awal,$tgl_akhir,$category){
    include "../../../configuration/connection_inc.php";
	$x = "select sum(a.subtotal*0.875*0.99)as total
		from sales_order_item  a,sales_order b, distributor c where
		a.quotation_id=b.quotation_id  and b.distributor_id = c.distributor_id and c.distributor_group = 'LOKAL' and
		CONVERT(VARCHAR(10), quotation_date, 120) between '$tgl_awal' and '$tgl_akhir' 
		and a.product_id in(select product_id from product where category='$category')";
	$qx = odbc_exec($conn2,$x);
	$rx = odbc_fetch_array($qx);
	return $rx[total];
}

function so_divisi_plus($tgl_awal,$tgl_akhir,$category){
    include "../../../configuration/connection_inc.php";
	$x = "select sum(a.subtotal*0.875*0.99)as total
		from sales_order_item  a,sales_order b, distributor c where
		a.quotation_id=b.quotation_id  and b.distributor_id = c.distributor_id and c.distributor_group = 'LOKAL' and
		CONVERT(VARCHAR(10), quotation_date, 120) between '$tgl_awal' and '$tgl_akhir' 
		and a.product_id in(select product_id from product where category='$category')and a.quantity>0";
	$qx = odbc_exec($conn2,$x);
	$rx = odbc_fetch_array($qx);
	return $rx[total];
}

function so_divisi_min($tgl_awal,$tgl_akhir,$category){
    include "../../../configuration/connection_inc.php";
	$x = "select sum(a.subtotal*0.875*0.99)as total
		from sales_order_item  a,sales_order b, distributor c where
		a.quotation_id=b.quotation_id  and b.distributor_id = c.distributor_id and c.distributor_group = 'LOKAL' and
		CONVERT(VARCHAR(10), quotation_date, 120) between '$tgl_awal' and '$tgl_akhir' 
		and a.product_id in(select product_id from product where category='$category') and a.quantity<0";
	$qx = odbc_exec($conn2,$x);
	$rx = odbc_fetch_array($qx);
	return $rx[total];
}

function do_divisi($tgl_awal,$tgl_akhir,$category){
    include "../../../configuration/connection_inc.php";
	$x = "select sum(a.quantity*b.sale_price*0.875*0.99) as total from kinosentrajit.dbo.do_item a,
		 sales_order_item b,sales_order c,kinosentrajit.dbo.do d, distributor e
		 where b.quotation_id=c.quotation_id and a.quotation_id=b.quotation_id and a.product_id=b.product_id and
         a.do_id=d.do_id and d.ok=1 and  c.distributor_id=e.distributor_id and e.distributor_group='LOKAL' and
		 CONVERT(VARCHAR(10), c.quotation_date, 120) between '$tgl_awal' and '$tgl_akhir' and a.product_id in
		(select product_id from product where category='$category')and a.quantity>0";
	$qx = odbc_exec($conn2,$x);
	$rx = odbc_fetch_array($qx);
	return $rx[total];
}

function do_divisi_plus($tgl_awal,$tgl_akhir,$category){
    include "../../../configuration/connection_inc.php";
	$x = "select sum(a.quantity*b.sale_price*0.875*0.99) as total from kinosentrajit.dbo.do_item a,
		 sales_order_item b,sales_order c,kinosentrajit.dbo.do d, distributor e
		 where b.quotation_id=c.quotation_id and a.quotation_id=b.quotation_id and a.product_id=b.product_id and
         a.do_id=d.do_id and d.ok=1 and  c.distributor_id=e.distributor_id and e.distributor_group='LOKAL' and
		 CONVERT(VARCHAR(10), c.quotation_date, 120) between '$tgl_awal' and '$tgl_akhir' and a.product_id in
		(select product_id from product where category='$category')and a.quantity>0";
	$qx = odbc_exec($conn2,$x);
	$rx = odbc_fetch_array($qx);
	return $rx[total];
}

function do_divisi_min($tgl_awal,$tgl_akhir,$category){
    include "../../../configuration/connection_inc.php";
	$x = "select sum(a.quantity*b.sale_price*0.875*0.99) as total from kinosentrajit.dbo.do_item a,
		 sales_order_item b,sales_order c,kinosentrajit.dbo.do d, distributor e
		 where b.quotation_id=c.quotation_id and a.quotation_id=b.quotation_id and a.product_id=b.product_id and
         a.do_id=d.do_id and d.ok=1 and  c.distributor_id=e.distributor_id and e.distributor_group='LOKAL' and
		 CONVERT(VARCHAR(10), c.quotation_date, 120) between '$tgl_awal' and '$tgl_akhir' and a.product_id in
		(select product_id from product where category='$category')and a.quantity<0";
	$qx = odbc_exec($conn2,$x);
	$rx = odbc_fetch_array($qx);
	return $rx[total];
}

function netsales_divisi($tgl_awal,$tgl_akhir,$category){
    include "../../../configuration/connection_inc.php";
	$x = "select sum(a.quantity*b.sale_price*0.875*0.99) as total from kinosentrajit.dbo.do_item a,
		 sales_order_item b,sales_order c,kinosentrajit.dbo.do d, distributor e
		 where b.quotation_id=c.quotation_id and a.quotation_id=b.quotation_id and a.product_id=b.product_id and
         a.do_id=d.do_id and d.ok=1 and  c.distributor_id=e.distributor_id and e.distributor_group='LOKAL' and
		 CONVERT(VARCHAR(10), c.quotation_date, 120) between '$tgl_awal' and '$tgl_akhir' and a.product_id in
		(select product_id from product where category='$category')";
	$qx = odbc_exec($conn2,$x);
	$rx = odbc_fetch_array($qx);
	return $rx[total];
}



function so_brand($tgl_awal,$tgl_akhir,$brand){
    include "../../../configuration/connection_inc.php";
	$x = "select sum(a.subtotal*0.875*0.99) as total
		from sales_order_item  a,sales_order b where
		a.quotation_id=b.quotation_id and 
		CONVERT(VARCHAR(10), quotation_date, 120) between '$tgl_awal' and '$tgl_akhir' and a.product_brand='$brand'";
	$qx = odbc_exec($conn2,$x);
	$rx = odbc_fetch_array($qx);
	return $rx[total];
}

function do_brand($tgl_awal,$tgl_akhir,$brand){
    include "../../../configuration/connection_inc.php";
	$x = "select sum(a.quantity*b.sale_price*0.875*0.99)  as total from kinosentrajit.dbo.do_item a,
		 sales_order_item b,sales_order c,kinosentrajit.dbo.do d
		 where b.quotation_id=c.quotation_id and a.quotation_id=b.quotation_id and a.product_id=b.product_id and
         a.do_id=d.do_id and d.ok=1 and  
		 CONVERT(VARCHAR(10), c.quotation_date, 120) between '$tgl_awal' and '$tgl_akhir' and a.product_id in
		(select product_id from product where brand='$brand')and a.quantity>0";
	$qx = odbc_exec($conn2,$x);
	$rx = odbc_fetch_array($qx);
	return $rx[total];
}

function netsales_brand($tgl_awal,$tgl_akhir,$brand){
    include "../../../configuration/connection_inc.php";
	$x = "select distinct c.brand,sum(d.quantity*a.sale_price*0.875*0.99)as total
		from sales_order_item  a,sales_order b, product c, kinosentrajit.dbo.do_item d where
		a.quotation_id=b.quotation_id and a.product_id=c.product_id and a.quotation_id=d.quotation_id and d.product_id=c.product_id and
		CONVERT(VARCHAR(10), b.quotation_date, 120) between '$tgl_awal' and '$tgl_akhir' and c.brand='$brand'
		group by c.brand";
	$qx = odbc_exec($conn2,$x);
	$rx = odbc_fetch_array($qx);
	return $rx[total];
}

function so_subbrand($tgl_awal,$tgl_akhir,$subbrand){
    include "../../../configuration/connection_inc.php";
	$x = "select sum(a.subtotal*0.875*0.99) as total
		from sales_order_item  a,sales_order b where
		a.quotation_id=b.quotation_id and
		CONVERT(VARCHAR(10), quotation_date, 120) between '$tgl_awal' and '$tgl_akhir' and a.subrand='$subbrand'";
	$qx = odbc_exec($conn2,$x);
	$rx = odbc_fetch_array($qx);
	return $rx[total];
}

function do_subbrand($tgl_awal,$tgl_akhir,$subbrand){
    include "../../../configuration/connection_inc.php";
	$x = "select sum(a.quantity*b.sale_price*0.875*0.99)  as total from kinosentrajit.dbo.do_item a,
		 sales_order_item b,sales_order c, kinosentrajit.dbo.do d
		 where b.quotation_id=c.quotation_id and a.quotation_id=b.quotation_id and a.product_id=b.product_id and
         a.do_id=d.do_id and d.ok=1 and  
		 CONVERT(VARCHAR(10), c.quotation_date, 120) between '$tgl_awal' and '$tgl_akhir' and a.product_id in
		(select product_id from product where subbrand='$subbrand')and a.quantity>0";
	$qx = odbc_exec($conn2,$x);
	$rx = odbc_fetch_array($qx);
	return $rx[total];
}

function netsales_subbrand($tgl_awal,$tgl_akhir,$subbrand){
    include "../../../configuration/connection_inc.php";
	$x = "select distinct c.subrand,sum(d.quantity*a.sale_price*0.875*0.99)as total
		from sales_order_item  a,sales_order b, product c, kinosentrajit.dbo.do_item d where
		a.quotation_id=b.quotation_id and a.product_id=c.product_id and a.quotation_id=d.quotation_id and d.product_id=c.product_id and
		CONVERT(VARCHAR(10), b.quotation_date, 120) between '$tgl_awal' and '$tgl_akhir' and c.subrand='$subbrand'
		group by c.subrand";
	$qx = odbc_exec($conn2,$x);
	$rx = odbc_fetch_array($qx);
	return $rx[total];
}

function so_product($tgl_awal,$tgl_akhir,$product){
    include "../../../configuration/connection_inc.php";
	$x = "select sum(a.subtotal*0.875*0.99)as total
		from sales_order_item  a,sales_order b, distributor c where
		a.quotation_id=b.quotation_id and b.distributor_id = c.distributor_id and c.distributor_group ='LOKAL' and
		CONVERT(VARCHAR(10), quotation_date, 120) between '$tgl_awal' and '$tgl_akhir' and a.product_id='$product'";
	$qx = odbc_exec($conn2,$x);
	$rx = odbc_fetch_array($qx);
	return $rx[total];
}

function do_product($tgl_awal,$tgl_akhir,$product){
    include "../../../configuration/connection_inc.php";
	$x = "select sum(a.quantity*b.sale_price*0.875*0.99)  as total from kinosentrajit.dbo.do_item a,
		 sales_order_item b,sales_order c, kinosentrajit.dbo.do d, distributor e
		 where b.quotation_id=c.quotation_id and a.quotation_id=b.quotation_id and a.product_id=b.product_id and
         a.do_id=d.do_id and d.ok=1 and c.distributor_id = e.distributor_id and e.distributor_group = 'LOKAL' and
		 CONVERT(VARCHAR(10), c.quotation_date, 120) between '$tgl_awal' and '$tgl_akhir' and a.product_id in
		(select product_id from product where product_id='$product')and a.quantity>0";
	$qx = odbc_exec($conn2,$x);
	$rx = odbc_fetch_array($qx);
	return $rx[total];
}

function so_product_quantity($tgl_awal,$tgl_akhir,$product){
    include "../../../configuration/connection_inc.php";
	$x = "select sum(a.quantity)as total
		from sales_order_item  a,sales_order b, distributor c where
		a.quotation_id=b.quotation_id and b.distributor_id = c.distributor_id and c.distributor_group ='LOKAL' and
		CONVERT(VARCHAR(10), quotation_date, 120) between '$tgl_awal' and '$tgl_akhir' and a.product_id='$product'";
	$qx = odbc_exec($conn2,$x);
	$rx = odbc_fetch_array($qx);
	return $rx[total];
}

function do_product_quantity($tgl_awal,$tgl_akhir,$product){
    include "../../../configuration/connection_inc.php";
	$x = "select sum(a.quantity)  as total from kinosentrajit.dbo.do_item a,
		 sales_order_item b,sales_order c, kinosentrajit.dbo.do d, distributor e
		 where b.quotation_id=c.quotation_id and a.quotation_id=b.quotation_id and a.product_id=b.product_id and
         a.do_id=d.do_id and d.ok=1 and c.distributor_id = e.distributor_id and e.distributor_group = 'LOKAL' and
		 CONVERT(VARCHAR(10), c.quotation_date, 120) between '$tgl_awal' and '$tgl_akhir' and a.product_id in
		(select product_id from product where product_id='$product')and a.quantity>0";
	$qx = odbc_exec($conn2,$x);
	$rx = odbc_fetch_array($qx);
	return $rx[total];
}

function stok($product){
	include "../../../configuration/connection_inc.php";
	$x = "select sum(quantity)as total from kinosentrajit.dbo.product where product_id='$product'";
	$qx = odbc_exec($conn2,$x);
	$rx = odbc_fetch_array($qx);
	return $rx[total];
}

function netsales_product($tgl_awal,$tgl_akhir,$product){
    include "../../../configuration/connection_inc.php";
	$x = "select distinct c.product_id,sum(d.quantity*a.sale_price*0.875*0.99)as total
		from sales_order_item  a,sales_order b, product c, kinosentrajit.dbo.do_item d, distributor e where
		a.quotation_id=b.quotation_id and a.product_id=c.product_id and a.quotation_id=d.quotation_id and d.product_id=c.product_id and
		b.distributor_id = e.distributor_id and e.distributor_group = 'LOKAL' and
		CONVERT(VARCHAR(10), b.quotation_date, 120) between '$tgl_awal' and '$tgl_akhir' and c.product_id='$product'
		group by c.product_id";
	$qx = odbc_exec($conn2,$x);
	$rx = odbc_fetch_array($qx);
	return $rx[total];
}

//============================================== export function =====================================================================

function target_export($tgl,$category_id){
	include "../../../configuration/connection_inc.php";
	$bln = date('m',strtotime($tgl));
	$thn = date('Y',strtotime($tgl));
	$x = mysql_query("SELECT a.value FROM sales_order_target a,sales_order_divisi b WHERE
					 a.divisi_id=b.divisi_id and  a.bulan=$bln and a.tahun=$thn and b.divisi_id='$category_id'");
	$rx = mysql_fetch_array($x);
	return $rx[value];
}

function basic_target_export($tgl,$category_id){
	include "../../../configuration/connection_inc.php";
	$bln = date('m',strtotime($tgl));
	$thn = date('Y',strtotime($tgl));
	$x = mysql_query("SELECT a.basic FROM sales_order_target a,sales_order_divisi b WHERE
					 a.divisi_id=b.divisi_id and  a.bulan=$bln and a.tahun=$thn and b.divisi_id='$category_id'");
	$rx = mysql_fetch_array($x);
	return $rx[basic];
}

function lastyear_export($tgl_awal,$tgl_akhir,$category){
    include "../../../configuration/connection_inc.php";
	$x = "select sum(a.quantity*f.c_rate*b.sale_price) as total from kinosentrajit.dbo.do_item a,
		 sales_order_item b,sales_order c,kinosentrajit.dbo.do d, distributor e,currency f
		 where b.quotation_id=c.quotation_id and a.quotation_id=b.quotation_id and a.product_id=b.product_id and
         a.do_id=d.do_id and d.ok=1 and  c.distributor_id=e.distributor_id and e.distributor_group='EXPORT' and
		 substring(CONVERT(VARCHAR(10), c.quotation_date, 120),1,7) = substring('$tgl_awal',1,7) and a.product_id in
		(select product_id from product where category='$category')and a.quantity>0
		and c.c_symbol=f.c_symbol and f.c_type=3 and c.quotation_date between f.c_from and f.c_to";
	$qx = odbc_exec($conn2,$x);
	$rx = odbc_fetch_array($qx);
	return $rx[total];
}

function so_divisi_plus_export($tgl_awal,$tgl_akhir,$category){
    include "../../../configuration/connection_inc.php";
	$x = "select sum(a.subtotal*d.c_rate)as total
			from sales_order_item  a,sales_order b, distributor c,currency d where
			a.quotation_id=b.quotation_id  and b.distributor_id = c.distributor_id and c.distributor_group = 'EXPORT' and
			CONVERT(VARCHAR(10), quotation_date, 120) between '$tgl_awal' and '$tgl_akhir' 
			and a.product_id in(select product_id from product where category='$category')and a.quantity>0
			and b.c_symbol=d.c_symbol and d.c_type=3 and b.quotation_date between d.c_from and d.c_to";
	$qx = odbc_exec($conn2,$x);
	$rx = odbc_fetch_array($qx);
	return $rx[total];
}


function so_divisi_min_export($tgl_awal,$tgl_akhir,$category){
    include "../../../configuration/connection_inc.php";
	$x = "select sum(a.subtotal*d.c_rate)as total
			from sales_order_item  a,sales_order b, distributor c,currency d where
			a.quotation_id=b.quotation_id  and b.distributor_id = c.distributor_id and c.distributor_group = 'EXPORT' and
			CONVERT(VARCHAR(10), quotation_date, 120) between '$tgl_awal' and '$tgl_akhir' 
			and a.product_id in(select product_id from product where category='$category')and a.quantity<0
			and b.c_symbol=d.c_symbol and d.c_type=3 and b.quotation_date between d.c_from and d.c_to";
	$qx = odbc_exec($conn2,$x);
	$rx = odbc_fetch_array($qx);
	return $rx[total];
}

function do_divisi_plus_export($tgl_awal,$tgl_akhir,$category){
    include "../../../configuration/connection_inc.php";
	$x = "select sum(a.quantity*f.c_rate*b.sale_price) as total from kinosentrajit.dbo.do_item a,
		 sales_order_item b,sales_order c,kinosentrajit.dbo.do d, distributor e,currency f
		 where b.quotation_id=c.quotation_id and a.quotation_id=b.quotation_id and a.product_id=b.product_id and
         a.do_id=d.do_id and d.ok=1 and  c.distributor_id=e.distributor_id and e.distributor_group='EXPORT' and
		 CONVERT(VARCHAR(10), c.quotation_date, 120) between '$tgl_awal' and '$tgl_akhir' and a.product_id in
		(select product_id from product where category='$category')and a.quantity>0
		and c.c_symbol=f.c_symbol and f.c_type=3 and c.quotation_date between f.c_from and f.c_to";
	$qx = odbc_exec($conn2,$x);
	$rx = odbc_fetch_array($qx);
	return $rx[total];
}


function do_product_export($tgl_awal,$tgl_akhir,$product){
    include "../../../configuration/connection_inc.php";
	$x = "select sum(a.quantity*b.sale_price)  as total from kinosentrajit.dbo.do_item a,
		 sales_order_item b,sales_order c, kinosentrajit.dbo.do d, distributor e
		 where b.quotation_id=c.quotation_id and a.quotation_id=b.quotation_id and a.product_id=b.product_id and
         a.do_id=d.do_id and c.distributor_id=e.distributor_id and d.ok=1 and  
		 CONVERT(VARCHAR(10), c.quotation_date, 120) between '$tgl_awal' and '$tgl_akhir' and a.product_id in
		(select product_id from product where product_id='$product')and a.quantity>0 and e.distributor_group='export'";
	$qx = odbc_exec($conn2,$x);
	$rx = odbc_fetch_array($qx);
	return $rx[total];
}

function do_product_export2($tgl_awal,$tgl_akhir,$product,$rate){
    include "../../../configuration/connection_inc.php";
	$x = "select a.product_id,f.c_rate,sum(a.quantity*b.sale_price)  as total from kinosentrajit.dbo.do_item a,
		 sales_order_item b,sales_order c, kinosentrajit.dbo.do d, distributor e,currency f
		 where b.quotation_id=c.quotation_id and a.quotation_id=b.quotation_id and a.product_id=b.product_id and
         a.do_id=d.do_id and c.distributor_id=e.distributor_id and d.ok=1 and  
		 CONVERT(VARCHAR(10), c.quotation_date, 120) between '$tgl_awal' and '$tgl_akhir' and a.product_id in
		(select product_id from product where product_id='$product')and a.quantity>0 
		and e.distributor_group='export' and c.c_symbol=f.c_symbol and f.c_type=3  and c_rate=$rate
		and c.quotation_date between f.c_from and f.c_to group by a.product_id,f.c_rate";
	
	$qx = odbc_exec($conn2,$x);
	$rx = odbc_fetch_array($qx);
	return $rx[total];
}



//=========================================================TOTAL ALL ================================================================

function target_all($tgl,$category_id){
	include "../../../configuration/connection_inc.php";
	$bln = date('m',strtotime($tgl));
	$thn = date('Y',strtotime($tgl));
	$x = mysql_query("SELECT sum(value)as value FROM sales_order_target WHERE bulan=$bln and tahun=$thn 
	                  and substring(reverse(divisi_id),1,1)='$category_id'");
	$rx = mysql_fetch_array($x);
	return $rx[value];
}

function basic_target_all($tgl,$category_id){
	include "../../../configuration/connection_inc.php";
	$bln = date('m',strtotime($tgl));
	$thn = date('Y',strtotime($tgl));
	$x = mysql_query("SELECT sum(basic)as basic FROM sales_order_target WHERE bulan=$bln and tahun=$thn 
	                  and substring(reverse(divisi_id),1,1)='$category_id'");
	$rx = mysql_fetch_array($x);
	return $rx[basic];
}

function lastyear_all($tgl_awal,$tgl_akhir,$category){
    include "../../../configuration/connection_inc.php";
	$x = "select sum(a.quantity*f.c_rate*b.sale_price*0.875*0.99) as total from kinosentrajit.dbo.do_item a,
		 sales_order_item b,sales_order c,kinosentrajit.dbo.do d, distributor e, currency f
		 where b.quotation_id=c.quotation_id and a.quotation_id=b.quotation_id and a.product_id=b.product_id and
         a.do_id=d.do_id and d.ok=1 and  c.distributor_id=e.distributor_id and
		 CONVERT(VARCHAR(10), c.quotation_date, 120) between '$tgl_awal' and '$tgl_akhir' and a.product_id in
		(select product_id from product where category='$category')and a.quantity>0
		and c.c_symbol=f.c_symbol and f.c_type=3 and c.quotation_date between f.c_from and f.c_to";
	$qx = odbc_exec($conn2,$x);
	$rx = odbc_fetch_array($qx);
	return $rx[total];
}

function so_divisi_plus_all($tgl_awal,$tgl_akhir,$category){
    include "../../../configuration/connection_inc.php";
	$x = "select sum(a.subtotal*d.c_rate*0.875*0.99)as total
			from sales_order_item  a,sales_order b, distributor c,currency d where
			a.quotation_id=b.quotation_id  and b.distributor_id = c.distributor_id and
			CONVERT(VARCHAR(10), quotation_date, 120) between '$tgl_awal' and '$tgl_akhir' 
			and a.product_id in(select product_id from product where category='$category')and a.quantity>0
			and b.c_symbol=d.c_symbol and d.c_type=3 and b.quotation_date between d.c_from and d.c_to";
	$qx = odbc_exec($conn2,$x);
	$rx = odbc_fetch_array($qx);
	return $rx[total];
}

function so_divisi_min_all($tgl_awal,$tgl_akhir,$category){
    include "../../../configuration/connection_inc.php";
	$x = "select sum(a.subtotal*d.c_rate*0.875*0.99)as total
			from sales_order_item  a,sales_order b, distributor c,currency d where
			a.quotation_id=b.quotation_id  and b.distributor_id = c.distributor_id and
			CONVERT(VARCHAR(10), quotation_date, 120) between '$tgl_awal' and '$tgl_akhir' 
			and a.product_id in(select product_id from product where category='$category')and a.quantity<0
			and b.c_symbol=d.c_symbol and d.c_type=3 and b.quotation_date between d.c_from and d.c_to";
	$qx = odbc_exec($conn2,$x);
	$rx = odbc_fetch_array($qx);
	return $rx[total];
}

function do_divisi_plus_all($tgl_awal,$tgl_akhir,$category){
    include "../../../configuration/connection_inc.php";
	$x = "select sum(a.quantity*f.c_rate*b.sale_price*0.875*0.99) as total from kinosentrajit.dbo.do_item a,
		 sales_order_item b,sales_order c,kinosentrajit.dbo.do d, distributor e,currency f
		 where b.quotation_id=c.quotation_id and a.quotation_id=b.quotation_id and a.product_id=b.product_id and
         a.do_id=d.do_id and d.ok=1 and  c.distributor_id=e.distributor_id and
		 CONVERT(VARCHAR(10), c.quotation_date, 120) between '$tgl_awal' and '$tgl_akhir' and a.product_id in
		(select product_id from product where category='$category')and a.quantity>0
		and c.c_symbol=f.c_symbol and f.c_type=3 and c.quotation_date between f.c_from and f.c_to";
	$qx = odbc_exec($conn2,$x);
	$rx = odbc_fetch_array($qx);
	return $rx[total];
}
?>