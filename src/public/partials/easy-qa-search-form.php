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

<!-- Begin Easy QA Forms -->

<div class="easy-qa-form">

  <ul class="easy-qa-tabs" role="tablist">
    <li role="presentation" class="active">
      <a href="#easy-qa-search" aria-controls="easy-qa-search" role="tab" data-toggle="tab">
        <span class="glyphicon glyphicon-search" aria-hidden="true"></span> Search
      </a>
    </li>
    <li role="presentation">
      <a href="#easy-qa-ask" aria-controls="easy-qa-ask" role="tab" data-toggle="tab">
        <span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span> Ask
      </a>
    </li>
  </ul>

  <div class="easy-qa-tab-content">

    <!-- Begin Easy QA Search Form -->

    <form action="<?php bloginfo('siteurl'); ?>" method="get" role="tab-panel" class="active" id="easy-qa-search">
      <input type="hidden" name="post_type" value="easy_qa_question" />
      <div class="input-group">
        <input type="text" class="form-control" name="s" placeholder="Search questions..." maxlength="140" value="<?php echo get_search_query(); ?>" required />
        <span class="input-group-btn">
          <button type="submit" class="btn btn-default">
            Submit
          </button>
        </span>
      </div>
    </form>

    <!-- End Easy QA Search Form -->

    <!-- Begin Easy QA Ask Form -->

    <form action="" method="post" role="tab-panel" id="easy-qa-ask">
      <input type="hidden" name="action" value="add_easy_qa_question" />

      <div class="form-group">
        <input type="text" class="form-control" name="easy_qa_ask[question]" placeholder="Enter question..." maxlength="140" required />
      </div>

      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <input type="text" class="form-control" name="easy_qa_ask[name]" placeholder="Your Name" required />
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <input type="email" class="form-control" name="easy_qa_ask[email]" placeholder="Your Email Address" required />
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <input type="text" class="form-control" name="easy_qa_ask[city]" placeholder="Your City" required />
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <input type="text" class="form-control" name="easy_qa_ask[state]" placeholder="Your State" required />
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <div class="form-group">
            <div class="recaptcha"></div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <div class="form-group">
            <button class="btn btn-default" type="submit">Submit</button>
          </div>
        </div>
      </div>

    </form>

    <!-- End Easy QA Ask Form -->

  </div>
</div>

<!-- End Easy QA Forms -->
