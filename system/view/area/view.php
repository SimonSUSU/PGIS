<? $this->load_view('min_header'); ?>

<div class="noticeDetails">
	<h2><?=$rs['title']?></h2>
	<h6><?=$rs['add_time_str']?></h6>
	<ul><?=$rs['content']?></ul>
</div>

<? $this->load_view('min_footer'); ?>