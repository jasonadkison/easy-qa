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

<div id="easy-qa-form" class="easy-qa-form col-xs-12 col-md-10 col-lg-8">

	<div class="easy-qa-nav btn-group" role="group">
		<button type="button" class="btn btn-default active" data-toggle-form="search">Search</button>
		<button type="button" class="btn btn-default" data-toggle-form="ask">Ask</button>
	</div>

	<form action="<?php bloginfo('siteurl'); ?>" method="get" data-form="search" class="active">
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

	<form action="" method="post" data-form="ask">
		<div class="form-group">
			<input type="text" class="form-control" name="easy_qa_ask[question]" placeholder="Enter your question here" maxlength="140" required/>
			<input type="hidden" name="action" value="add_easy_qa_question" />
		</div>

		<div class="row">
			<div class="col-xs-6">
				<div class="form-group">
					<input type="text" class="form-control" name="easy_qa_ask[name]" placeholder="Name" required/>
				</div>
			</div>
			<div class="col-xs-6">
				<div class="form-group">
					<input type="email" class="form-control" name="easy_qa_ask[email]" placeholder="Email" required/>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-6">
				<div class="form-group">
					<input type="text" class="form-control" name="easy_qa_ask[city]" placeholder="City" required/>
				</div>
			</div>
			<div class="col-xs-6">
				<div class="form-group">
					<input type="text" class="form-control" name="easy_qa_ask[state]" placeholder="State" required/>
				</div>
			</div>
		</div>

		<div class="form-group">
			<div class="recaptcha"></div>
		</div>

		<div class="form-group">
			<button class="btn btn-default" type="submit">Ask</button>
		</div>
	</form>
</div>

<!-- End Easy QA Search Form -->