<?php
require_once __DIR__.'/../config/database.php';
date_default_timezone_set('Asia/Kolkata');
session_set_cookie_params(['httponly'=>true,'samesite'=>'Lax']);
session_start();
// Derive URL from the folder, so renaming mealbridge still works.
$base = str_replace('\\','/',dirname($_SERVER['SCRIPT_NAME']));
if (in_array(basename($base), ['admin','donor','orphanage','actions'])) $base=dirname($base);
define('BASE', rtrim($base,'/'));
function url($p=''){ return BASE.'/'.$p; }
function e($v){return htmlspecialchars((string)$v,ENT_QUOTES,'UTF-8');}
function go($p){header('Location: '.url($p));exit;}
function query($sql,$args=[]){$s=db()->prepare($sql);$s->execute($args);return $s;}
function user(){static $u=false;if($u===false)$u=isset($_SESSION['uid'])?query('SELECT * FROM users WHERE id=?',[$_SESSION['uid']])->fetch():null;return $u;}
function guard($role=null){$u=user();if(!$u)go('login.php');if($u['status']!=='approved' || ($role && $u['role']!==$role)){http_response_code(403);exit('Access denied. Your account must be approved and have the correct role.');}return $u;}
function csrf(){if(empty($_SESSION['csrf']))$_SESSION['csrf']=bin2hex(random_bytes(32));return '<input type="hidden" name="csrf" value="'.e($_SESSION['csrf']).'">';}
function check_csrf(){if($_SERVER['REQUEST_METHOD']!=='POST'||!hash_equals($_SESSION['csrf']??'',$_POST['csrf']??'!')){http_response_code(403);exit('Invalid form token. Reload the page and try again.');}}
function flash($text,$type='success'){$_SESSION['flash']=[$text,$type];}
function field($name,$max=500){$v=trim($_POST[$name]??'');if($v===''||strlen($v)>$max)throw new Exception('Please enter a valid '.str_replace('_',' ',$name).'.');return $v;}
function expire_food(){query("UPDATE donations SET status='expired' WHERE status='pending' AND deadline<NOW()");}
function event($id,$text){query('INSERT INTO donation_events(donation_id,actor_id,message) VALUES(?,?,?)',[$id,user()['id'],$text]);}
function badge($status){return '<span class="badge '.e($status).'">'.e(ucfirst($status==='pending'?'available':$status)).'</span>';}
function icon($name){$paths=['leaf'=>'M20 4C10 2 3 6 4 14c1 7 13 8 16-10ZM5 19 16 8','heart'=>'M20 5c-3-3-7-1-8 1-2-3-6-4-9 0-4 6 9 14 9 14s13-9 8-15Z','box'=>'m3 7 9-4 9 4v11l-9 4-9-4Zm0 0 9 4 9-4M12 11v11','arrow'=>'M4 12h16m-6-6 6 6-6 6','pin'=>'M19 10c0 5-7 11-7 11S5 15 5 10a7 7 0 1 1 14 0ZM12 8v4','clock'=>'M12 3a9 9 0 1 0 0 18 9 9 0 0 0 0-18Zm0 4v5l4 2','grid'=>'M3 3h7v7H3Zm11 0h7v7h-7ZM3 14h7v7H3Zm11 0h7v7h-7Z'];return '<svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="'.($paths[$name]??$paths['leaf']).'"/></svg>';}
function food_image($category){return 'assets/images/'.(['Cooked meals'=>'meal','Produce'=>'produce','Bakery'=>'bakery','Packaged food'=>'pantry'][$category]??'meal').'.svg';}
function cards($rows){if(!$rows){echo '<div class="empty">'.icon('box').'<h3>No donations here yet</h3><p>New food listings will appear here when donors share them.</p></div>';return;}echo '<div class="cards">';foreach($rows as $d){echo '<article class="food-card"><a href="'.url('donation-details.php?id='.$d['id']).'"><img src="'.url(food_image($d['category'])).'" alt="'.e($d['category']).' illustration"></a><div class="card-body"><div class="row">'.badge($d['status']).'<small>'.e($d['food_type']).'</small></div><h3><a href="'.url('donation-details.php?id='.$d['id']).'">'.e($d['title']).'</a></h3><p>'.icon('pin').' '.e($d['city']).' &nbsp; · &nbsp; '.(int)$d['quantity'].' servings</p><p class="muted">'.icon('clock').' Collect by '.e(date('d M, h:i A',strtotime($d['deadline']))).'</p><a class="text-link" href="'.url('donation-details.php?id='.$d['id']).'">View donation '.icon('arrow').'</a></div></article>';}echo '</div>';}
function input($label,$name,$type='text',$value='',$extra=''){echo '<label>'.e($label).'<input type="'.e($type).'" name="'.e($name).'" value="'.e($value).'" '.$extra.' required></label>';}
try { db(); expire_food(); } catch(PDOException $ex){http_response_code(503);exit('<h2>MealBridge needs a database connection</h2><p>Start MySQL in XAMPP, import database/mealbridge.sql, and check config/database.php. The default port is 3307.</p>');}
