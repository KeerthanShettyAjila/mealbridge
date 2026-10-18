<?php require '../includes/functions.php';guard('admin');check_csrf();$a=$_POST['action']??'';$id=(int)($_POST['id']??0);try{
if($a==='read'){query('UPDATE messages SET is_read=1 WHERE id=?',[$id]);flash('Message marked as read.');go('admin/messages.php');}
if(!in_array($a,['approve','reject']))throw new Exception('Invalid action.');
if($a==='reject'&&query("SELECT COUNT(*) FROM donations WHERE status='accepted' AND (donor_id=? OR orphanage_id=?)",[$id,$id])->fetchColumn())throw new Exception('This user has active collections. Complete or release those first.');
query("UPDATE users SET status=? WHERE id=? AND role<>'admin'",[$a==='approve'?'approved':'rejected',$id]);flash('Account status updated.');
}catch(Exception $e){flash($e instanceof PDOException?'Unable to update account.':$e->getMessage(),'error');}go('admin/users.php');