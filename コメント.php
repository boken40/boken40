＜コメント埋込部(PHP)＞
// HTMLを出力する前にsession_start();しておいて下さい
// ID生成にsession_id()を使います
// 以下をPHPファイルに直接埋め込むか別ファイルに記述しておきrequire()やinclude()で呼び出して下さい
<?php
// このPHPファイルをrequireした呼び出し元のディレクトリとファイル名を取得する
// 直接埋め込む場合は"$r_buff[0]['file']"を"__FILE__"に置き換えて下さい
$r_buff = @debug_backtrace();
if(isset($r_buff[0])){
	$r_name = preg_replace('/\.php\z/', '.dat', $r_buff[0]['file']);
} else{
	$r_name = false;
}
// コメントを取得する
if($r_name){
	if(file_exists($r_name)){
		$comments = json_decode(file_get_contents($r_name), true);
	} else{
		$comments = array();
	}
}
// 最後の10件だけ表示する
$comments = array_slice($comments , 0, 10);
?>
<aside id="show_comments">
	<div>
		<form>
			<h4>コメントする</h4>
			<div>
				<label for="comment_name">お名前</label>
				<input type="text" name="name" id="comment_name" placeholder="お名前 (省略可)">
				<input type="hidden" name="id" value="<?php echo substr(base_convert(md5(session_id()), 16, 36), 0, 6); ?>">
			</div>
			<div>
				<label for="comment_mail">メールアドレス</label>
				<input type="text" name="mail" id="comment_mail" placeholder="メールアドレス (省略可)">
			</div>
			<div>
				<label for="comment_website">Web サイト</label>
				<input type="text" name="url"  id="comment_website" placeholder="WebサイトなどのURL (省略可)">
			</div>
			<div>
				<label for="comment_text">コメント (必須)</label>
				<textarea id="comment_text" name="message" placeholder="コメントやご感想など" required></textarea>
			</div>
			<div>
				<button type="submit" id="comment_button">コメントを書き込む</button>
			</div>
		</form>
	</div>
	<div>
		<small>※短時間で連続してのコメントはできません。</small>
		<small>※いたずら防止のためにIP アドレスを記録しています。ご了承願います。</small>
	</div>
<?php
	if(count($comments) > 0){
?>
	<ul>
<?php
		foreach($comments as $msg){
?>
		<li>
			<div>
				<span><?php if(!empty($msg['mail'])){ echo '<a href="mailto:'. $msg['mail']. '" rel="noopener" target="new">'; } ?><?php echo empty($msg['name']) ? 'ゲスト' : $msg['name']; ?><?php if(!empty($msg['mail'])){ echo '</a>'; } ?>さん</span>
				<span>(ID：<?php echo $msg['id']; ?>)</span>
				<span>投稿日時：<?php echo $msg['time']; ?></span>
<?php if(!empty($msg['url'])){ ?>
				<span><a href="<?php echo $msg['url']; ?>" rel="noopener" target="new"><?php echo $msg['url']; ?></a></span>
<?php } ?>
			</div>
			<p>
				<?php echo $msg['message']; ?>
			</p>
		</li>
<?php
		}
?>
	</ul>
<?php
	}
?>
</aside>