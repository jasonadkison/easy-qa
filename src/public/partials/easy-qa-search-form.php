<?php
/**
 * Search Form Partial
 *
 * @link http://emptyset.co/wordpress/easy-qa
 * @since 1.0.0
 * @package Easy_QA
 * @subpackage Easy_QA/public/partials
 * @author jason@emptyset.co
 */
?>

<!-- Begin Easy QA Search Form -->

<div class="easy-qa easy-qa-form">

	<div class="easy-qa-nav btn-group unit whole" role="group">
		<button type="button" class="btn btn-default active" data-toggle-form="search">Search</button>
		<button type="button" class="btn btn-default" data-toggle-form="ask">Ask</button>
	</div>

	<form action="<?php bloginfo('siteurl'); ?>" method="get" data-form="search" class="active unit whole">
		<input type="hidden" name="post_type" value="easy_qa_question" />
		<div class="input-group">
			<input type="text" class="form-control" name="s" placeholder="Search questions here" maxlength="140" value="<?php echo get_search_query(); ?>" required />
			<span class="input-group-btn">
				<button type="submit" class="btn btn-default">
					<span class="glyphicon glyphicon-search"></span> Search
				</button>
			</span>
		</div>
	</form>

	<form action="" method="post" data-form="ask" class="unit whole">
		<div class="form-group grid">
			<input type="text" class="form-control" name="easy_qa_ask[question]" placeholder="Enter your question here" maxlength="140" required/>
			<input type="hidden" name="action" value="add_easy_qa_question" />
		</div>

		<div class="grid">
			<div class="unit half">
				<div class="grid">
					<input type="text" class="form-control" name="easy_qa_ask[name]" placeholder="Name" required/>
				</div>
			</div>
			<div class="unit half">
				<div class="grid">
					<input type="email" class="form-control" name="easy_qa_ask[email]" placeholder="Email" required/>
				</div>
			</div>
		</div>
		<div class="grid">
			<div class="unit half">
				<div class="grid">
					<input type="text" class="form-control" name="easy_qa_ask[city]" placeholder="City" required/>
				</div>
			</div>
			<div class="unit half">
				<div class="grid">
					<input type="text" class="form-control" name="easy_qa_ask[state]" placeholder="State" required/>
				</div>
			</div>
		</div>

		<div class="grid">
			<div class="unit four-fifths">
				<div class="recaptcha"></div>
			</div>
			<div class="unit one-fifth">
				<button class="btn btn-default" type="submit">Ask</button>
			</div>
		</div>

	</form>
</div>

<!-- End Easy QA Search Form -->
