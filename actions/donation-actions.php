<?php
require '../includes/functions.php';$u=guard();check_csrf();$a=$_POST['action']??'';$id=(int)($_POST['id']??0);
try {
 db()->beginTransaction();
 if(in_array($a,['create','edit'])){
 if($u['role']!=='donor')throw new Exception('Only donors can post food.');
 $title=field('title',120);$type=field('food_type',30);$cat=field('category',30);$qty=filter_var($_POST['quantity']??'',FILTER_VALIDATE_INT);
 $city=field('city',100);$address=field('address');$desc=field('description',3000);$allergens=field('allergens',255);$deadline=field('deadline',30);$dt=DateTime::createFromFormat('Y-m-d\TH:i',$deadline);
 if(!in_array($type,['Vegetarian','Non-vegetarian','Vegan'])||!in_array($cat,['Cooked meals','Produce','Bakery','Packaged food'])||!$qty||$qty<1||$qty>10000||!$dt||$dt->format('Y-m-d\TH:i')!==$deadline||$dt->getTimestamp()<=time())throw new Exception('Check food type, quantity (1–10,000) and future collection deadline.');
 $values=[$title,$type,$cat,$qty,$city,$address,$desc,$allergens,$dt->format('Y-m-d H:i:s')];
 if($a==='create'){query('INSERT INTO donations(title,food_type,category,quantity,city,address,description,allergens,deadline,donor_id) VALUES(?,?,?,?,?,?,?,?,?,?)',array_merge($values,[$u['id']]));$id=db()->lastInsertId();event($id,'Donation posted');}
 else{$d=query('SELECT * FROM donations WHERE id=? FOR UPDATE',[$id])->fetch();if(!$d||$d['donor_id']!=$u['id']||$d['status']!=='pending')throw new Exception('Only your available donations can be edited.');query('UPDATE donations SET title=?,food_type=?,category=?,quantity=?,city=?,address=?,description=?,allergens=?,deadline=? WHERE id=?',array_merge($values,[$id]));event($id,'Donation details updated');}
 } else {
 $d=query('SELECT * FROM donations WHERE id=? FOR UPDATE',[$id])->fetch();if(!$d)throw new Exception('Donation not found.');
 if($a==='accept'){
 if($u['role']!=='orphanage'||$d['status']!=='pending'||strtotime($d['deadline'])<=time())throw new Exception('This donation is no longer available.');
 $donor=query('SELECT status FROM users WHERE id=?',[$d['donor_id']])->fetchColumn();if($donor!=='approved')throw new Exception('This donor is not currently approved.');
 query("UPDATE donations SET status='accepted',orphanage_id=?,pickup_note=? WHERE id=?",[$u['id'],field('pickup_note'),$id]);event($id,'Accepted by '.$u['name']);
 }elseif(in_array($a,['collect','release','pickup'])){
 if($u['role']!=='orphanage'||$d['orphanage_id']!=$u['id']||$d['status']!=='accepted')throw new Exception('This collection does not belong to you or is already closed.');
 if($a==='collect'){query("UPDATE donations SET status='collected',collected_at=NOW() WHERE id=?",[$id]);event($id,'Food received — collection completed');}
 if($a==='pickup'){query('UPDATE donations SET pickup_note=? WHERE id=?',[field('pickup_note'),$id]);event($id,'Pickup arrangement updated');}
 if($a==='release'){query("UPDATE donations SET status=IF(deadline>NOW(),'pending','expired'),orphanage_id=NULL,pickup_note='' WHERE id=?",[$id]);event($id,'Acceptance cancelled by '.$u['name']);}
 }elseif($a==='cancel'){
 if($d['donor_id']!=$u['id']||$d['status']!=='pending')throw new Exception('Only your available donations can be cancelled.');query("UPDATE donations SET status='cancelled' WHERE id=?",[$id]);event($id,'Cancelled by donor');
 }else throw new Exception('Unknown donation action.');
 }
 db()->commit();flash('Donation updated successfully.');go('donation-details.php?id='.$id);
}catch(Exception $e){if(db()->inTransaction())db()->rollBack();flash($e instanceof PDOException?'Unable to save. Please try again.':$e->getMessage(),'error');go($id?'donation-details.php?id='.$id:'donor/add-donation.php');}
