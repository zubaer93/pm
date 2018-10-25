<script id="item-watchlist-template-new" type="text/template">
    <div class="col-sm-12 col-md-3 col-lg-3 mb-10">
        <div class="box-light">
            <div class="box-inner">
                <div class="box-messages">
                    <h4 class="mb-20">
                        {{name}}
                        <form name="post_5b6e12276f441472738262{{id}}" style="display:none;" method="post" action="/JMD/watchlist-{{item}}/delete/{{id}}"><input type="hidden" name="_method" value="POST"></form>
                        <a href="#" class="float-right mr-5" onclick="if (confirm(&quot;Are you sure to remove this item?&quot;)) { document.post_5b6e12276f441472738262{{id}}.submit(); } event.returnValue = false; return false;"><i class="fa fa-remove negative"></i></a>
                        <a href="javascript:;" class="float-right mr-5 js-watchlist" data-url="/JMD/watchlist-{{item}}/edit/{{id}}"><i class="fa fa-cog"></i></a>
                    </h4>
                </div>
                <div class="text-muted text-center">
                    <div id="box-watchlist-content">
                        <?= $this->Html->image("FrontendTheme._smarty/icon-watchlist.png", ["width" => "50%", "alt" => "Watchlist"]); ?>
                        <p class="fs-18 mb-6"><b><?= __('Your Watchlist'); ?></b></p>
                        <p class="fs-12 mb-10">
                            <?= __('Add to your watchlist for easy access to your favorite forex') ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</script>

<script id="watchlist-template-new" type="text/template">
    <div class="col-sm-12 col-md-3 col-lg-3 mb-10 mt-10">
        <div class="box-light ticker-container watchlist_div">
            <div class="box-inner">
                <div class="box-messages">
                    <h4 class="mb-20">
                        {{name}}
                        <a href="#" class="float-right" data-toggle="modal" data-target="#confirm-delete" data-url="{{url}}" tabindex="-1"><i class="fa fa-remove negative"></i></a>
                        <a href="#" class="float-right mr-5" data-toggle="modal" data-id ="{{id}}" data-name="{{name}}"  data-target="#setting-watch-list" tabindex="-1"><i class="fa fa-cog"></i></a>
                    </h4>
                </div>
                <div class="text-muted text-center">
                    <div id="box-watchlist-content{{id}}">
                        <?= $this->Html->image("FrontendTheme._smarty/icon-watchlist.png", ["width" => "50%", "alt" => "Watchlist"]); ?>
                        <p class="fs-18 mb-6"><b><?= __('Your Watchlist'); ?></b></p>
                        <p class="fs-12 mb-10">
                            <?= __('Add to your watchlist for easy access to your favorite stocks') ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</script>
<script id="watchlist-template" type="text/template">
    <li class="wl-item" data-symbol="{{ symbol }}">
    <div class="pricing" data-symbol="{{ symbol }}" data-stock-direction="{{ status }}">
    <a href="<?= $this->Url->build(['_name' => 'symbol', 'stock' => "{{ symbol }}"]) ?>">
    <div class="price-container">
    <h2 class="price">{{ bid }}</h2>
    <span class="change-image-arrow {{ status }}"></span>
    </div>
    <span class="change {{ status }}">{{ change }} ({{ percent_change }}) </span>
    </a>
    </div>
    <a href="<?= $this->Url->build(['_name' => 'symbol', 'stock' => "{{ symbol }}"]) ?>">
    <span class="js-price-color price-color-change {{ status }}" data-symbol="{{ symbol }}"></span>
    <h2>{{ symbol }}</h2>
    <span class="symbol-title">{{ name }}</span>
    </a>
    </li>
</script>
<script id="notification-template" type="text/template">
    <li class="<?= ("{{seen}}" == 1) ? 'read' : 'unread' ?>">
        <a data-id="{{id}}" href="{{url}}">{{title}}</a>
    </li>
    <li class="divider"></li>
</script>
<script id="trader-template" type="text/template">
    <span class="change-image {{ status }}"></span>
        <a href="javascript:;" data-toggle="tooltip" title="{{{ change_tooltip }}}">
            <span class="change font-size {{ status }}">
                {{ change }}
                ({{ percent_change }}%)
            </span>
        </a>
</script>

<script id="watchlist-button-unwatch-hover-template" type="text/template">
    <h2><i class="fa fa-remove icon"></i></h2> Unwatch <span class="nope" style="font-weight: bold;">{{ symbol }}</span>
</script>

<script id="watchlist-button-watch-hover-template" type="text/template">
    <h2><i class="fa fa-check icon"></i></h2> Watching <span class="nope" style="font-weight: bold;">{{ symbol }}</span>
</script>

<script id="watchlist-button-unwatch-template" type="text/template">
    <h2>+</h2> Add <span class="nope" style="font-weight: bold;">{{ symbol }}</span> to Watchlist
</script>

<script id="watchlist-button-watch-template" type="text/template">
    <h2><i class="fa fa-check icon"></i></h2> Watching <span class="nope" style="font-weight: bold;">{{ symbol }}</span>
</script>

<script id="search-company-template" type="text/template">
    <a class="clearfix" href="{{url}}" data-type="stock" data-query="{{ symbol }}">
    <div class="symbol">{{ symbol }}</div>
    <div class="symbol-name">{{ name }}</div>
    <div class="details">{{ exchange }}</div>
    </a>
</script>
<script id="search-trader-template" type="text/template">
    <a class="clearfix" href="<?= $this->Url->build(['_name' => 'forex_currency', 'currency' => "{{ from_currency_code }}-{{to_currency_code}}"]) ?>" data-type="stock" data-query="{{ from_currency_code }}-{{to_currency_code}}">
    <div class="symbol">{{ from_currency_code }}</div>
    <div class="symbol-name">{{ to_currency_code }}</div>
    <div class="details">{{ exchange }}</div>
    </a>
</script>

<script id="search-user-template" type="text/template">
    <a class="clearfix" href="<?= $this->Url->build(['_name' => 'user_name', 'username' => "{{ username }}"]) ?>" data-type="stock" data-query="{{ symbol }}">
    <div class="symbol"><img style="width:25px;" src='{{ icon }}' /></div>
    <div class="symbol-name">{{ fullname }} ({{ username }})</div>
    </a>
</script>

<script id="validate-user-logged" type="text/template">
    <div class="alert alert-mini alert-danger mb-30" onclick="this.classList.add('d-none');">
    You must be logged in to send messages.
    <?= $this->Html->link(__d('CakeDC/Users', 'Sign up'), ['_name' => 'register']); ?>
    or
    <?= $this->Html->link(__d('CakeDC/Users', 'Login'), ['_name' => 'login']); ?>.
    </div>
</script>

<script id="validate-user-logged-star" type="text/template">
    <div class="alert alert-mini alert-danger" onclick="this.classList.add('d-none');">
    You must be logged in to rate.
    <?= $this->Html->link(__d('CakeDC/Users', 'Sign up'), ['_name' => 'register']); ?>
    or
    <?= $this->Html->link(__d('CakeDC/Users', 'Login'), ['_name' => 'login']); ?>.
    </div>
</script>
<script id="validate-user-logged-delete" type="text/template">
    <div class="alert alert-mini alert-danger mb-30" onclick="this.classList.add('d-none');">
    Something went wrong.
    </div>
</script>
<script id="validate-user-logged-share" type="text/template">
    <div class="alert alert-mini alert-danger" onclick="this.classList.add('d-none');">
    You must be logged in to share.
    <?= $this->Html->link(__d('CakeDC/Users', 'Sign up'), ['_name' => 'register']); ?>
    or
    <?= $this->Html->link(__d('CakeDC/Users', 'Login'), ['_name' => 'login']); ?>.
    </div>
</script>
<script id="user-message-comment-toggle" type="text/template">
    <div class="toggle toggle-transparent toggle-noicon">
    <div class="toggle commnets-toggle">

        <label class="text-right">
            <a href="javascript:;" class="btn btn-primary relative comments_post" data-message-id="{{ message_id }}">
                <span class="badge badge-dark badge-corner radius-3 comments_post_count" data-message-id="{{ message_id }}">0</span>
                <i class="fa fa-comments fs-20 p-0"></i> Comment
            </a>
        </label>
        <div class="toggle-content" style="display: none;">
            <div class="comments-list" id="message-id-{{ message_id }}"></div>
            <div class="row">
                <div class="col-lg-1 col-md-1 col-sm-2">
                    <div class="thumbnail float-left avatar mr-20">
                    <a href="<?=
						$this->Url->build([
						'_name' => 'user_name',
						'username' => "{{ fullname }}"]);
						?>" >
							<img src="{{avatarPath}}" width="30" height="30" alt="" class="user_avatar">

                    </a>
					</div>
                </div>
                <div class="col-lg-11 col-md-11 col-sm-10">
                    <form method="post" accept-charset="utf-8" class="formMessage" action="<?=
						$this->Url->build([
						'_name' => 'add_post_comments'
						]);
						?>">
					<div style="display:none;"><input type="hidden" name="_method" value="POST"></div>
					<textarea name="message" class="message-box form-control h-40" placeholder="Write a comment..." data-maxlength="200" data-message-id="{{ message_id }}" rows="5"></textarea>
					<input type="hidden" name="message_id" value="{{ message_id }}">
					<button type="submit" class="btn btn-primary btn-send-message-comment btn-sm">
					<i class="fa fa-check"></i> Send</button>
					</form>
					</div>
            </div>
        </div>
    </div>
</div>
<hr>
</script>
<script id="user-message-comment" type="text/template">
    <li class="messageli_comment" id="message_{{ message_id }}" data-id="{{ message_id }}">
    <div class="stream-content">
    <div class="message">

    <div class="container">
     <div class="row">
        <div class="col-lg-1 col-md-1 col-sm-2">
            <div class="message-header">
                <div class="thumbnail float-left avatar mr-20">
                    <a href="<?=
                    $this->Url->build([
                    '_name' => 'user_name',
                    'username' => "{{ fullname }}"]);
                    ?>" >
                        <img src="{{avatarPath}}" width="30" height="30" alt="" class="user_avatar">

                    </a>
                </div>

            </div>
        </div>
        <div class="col-lg-10 col-md-10 col-sm-10 ml-10">
        <small>
            <div class="row">
                <span class="date">{{ date }}</span>
            </div>
            <div class="row">
                <a href="<?=
                $this->Url->build([
                '_name' => 'user_name',
                'username' => "{{ fullname }}"]);
                ?>" class="message-username"><b>{{ fullname }} -</b>
                    <cite style="color: black"  class="fs-12"><span class="experience">{{experience}}</span> <span class="investment">{{investment_style}}</span></cite>

                </a>
            </div>
            <div class="row">
                <div class="message-content">
                    <div class="message-body">
                        <?= "{{{message}}}" ?>
                    </div>
                    <div class="message-tools"></div>
                </div>
            </div>
            </small>
        </div>
    </div>
    </div>
    <!-- ALERT -->
    <div class="message_alert_star pr-10"></div>
    <!-- /ALERT -->

    </div>
    </div>
    </li>
    <hr>
</script>

<script id="user-send-messag-modal" type="text/template">
    <div class="stream-content">
    <div class="message">
    <div class="message-header">
    <div class="thumbnail float-left avatar mr-20">
    <a href="<?=
    $this->Url->build([
        '_name' => 'user_name',
        'username' => "{{ fullname }}"]);
    ?>">
    <img src="{{avatarPath}}" width="60" height="60" alt="">
    </a>
    </div>
    <div class="message-date">{{ date }}</div>
    <a href="<?=
    $this->Url->build([
        '_name' => 'user_name',
        'username' => "{{ fullname }}"]);
    ?>" class="message-username"><b>{{ fullname }} -</b>
    <cite style="color: black"  class="fs-12"> <span class="experience"> {{experience}}</span> <span class="investment">{{investment_style}}</span></cite>

    </a>
    <div class="message-content">
    <div class="message-body"><?= "{{{ message }}}" ?></div>
    <div class="message-tools"></div>
    </div>
    </div>
    </div>
    </div>
</script>
<script id="user-send-message" type="text/template">
    <li class="messageli" id="message_{{ message_id }}" data-id="{{ message_id }}">
    <div class="stream-content">
    <div class="message">

    <div class="container">
     <div class="row">
        <div class="col-lg-2 col-md-2 col-sm-12 mt-5 mrp-1">
            <div class="message-header">
                <div class="thumbnail float-left avatar mr-20">
                    <a href="<?=
                    $this->Url->build([
                    '_name' => 'user_name',
                    'username' => "{{ fullname }}"]);
                    ?>" >
                        <img src="{{avatarPath}}" width="60" height="60" alt="" class="user_avatar">

                    </a>
                </div>

            </div>
        </div>
        <div class="col-lg-10 col-md-10 col-sm-12 mb-20m margin_left_0">
            <div class="row">
                <span class="date">{{ date }}</span>

                <span class="mr-5 ml-10"> <?= __('Rate:') ?></span>
                <div class="stars mb-10m" data-id="{{ message_id }}" >
                    <?= "{{{stars}}}" ?>
                </div>
                <span class="ml-5 mr-10 rate">
                   {{ rating }}.0
                </span>
                <a href="#" data-toggle ='modal' data-id="{{ message_id }}" data-target='#modal_share' class="modal_share">
                    <span><?= __('SHARE'); ?></span>
                    <i class="fa fa-share" aria-hidden="true"></i>
                </a>
                <span class="ml-10" {{delete_status}}>
                    <a href="javascript:;" data-id="{{ message_id }}" data-toggle="modal" class="delete_share">
                        <i class="fa fa-remove negative" aria-hidden="true"></i>
                    </a>
                </span>
            </div>
            <div class="row">
                <a href="<?=
                $this->Url->build([
                '_name' => 'user_name',
                'username' => "{{ fullname }}"]);
                ?>" class="message-username"><b>{{ fullname }} -</b>
                    <cite style="color: black"  class="fs-12"><span class="experience">{{experience}}</span> <span class="investment">{{investment_style}}</span></cite>

                </a>
            </div>
            <div class="row">
                <div class="message-content">
                    <div class="message-body">
                        <?= "{{{message}}}" ?>
                    </div>
                    <div class="message-tools"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 mt-5">
            <div class="mydiv_iframe" style="display:{{status}}">
                     <a href="{{url_page}}"><img src="data:image/jpeg;base64,{{img_data}}" height=\"200\" /></a>
            </div>
        </div>
    </div>
    </div>
    <!-- ALERT -->
    <div class="message_alert_star pr-10"></div>
    <!-- /ALERT -->

    </div>
    </div>
    </li>
</script>
<script id="user-share-message"  type="text/template">
  <li  class="messageli" id="message_{{ message_id }}" data-id="{{ message_id }}">
        <div class="stream-content">
            <div class="message">
             <div class="container">
                <div class="row">
                    <div class="col-lg-2 col-md-2 col-sm-12 mt-5 mrp-1">
                        <div class="message-header">
                            <div class="thumbnail float-left avatar mr-20">
                                <a href="<?=
                                $this->Url->build([
                                '_name' => 'user_name',
                                'username' => "{{ fullname }}"]);
                                ?>" >
                                    <img src="{{avatarPath}}" width="60" height="60" alt="" class="user_avatar">

                                </a>
                            </div>

                        </div>
                    </div>
                    <div class="col-lg-10 col-md-10 col-sm-12 mb-20m margin_left_0">
                        <div class="row">
                            <span class="date">{{ date }}</span>

                            <span class="mr-5 ml-10"> <?= __('Rate:') ?></span>
                            <div class="stars mb-10m" data-id="{{ message_id }}" >
                                <?= "{{{stars}}}" ?>
                            </div>
                            <span class="ml-5 mr-10 rate">
                               {{ rating }}.0
                            </span>
                            <a href="#" data-toggle ='modal' data-id="{{ message_id }}" data-target='#modal_share' class="modal_share">
                                <span><?= __('SHARE'); ?></span>
                                <i class="fa fa-share" aria-hidden="true"></i>
                            </a>
                            <span class="ml-10" {{delete_status}}>
                                <a href="javascript:;" data-id="{{ message_id }}" data-toggle="modal" class="delete_share">
                                    <i class="fa fa-remove negative" aria-hidden="true"></i>
                                </a>
                            </span>
                        </div>
                        <div class="row">
                            <a href="<?=
                            $this->Url->build([
                            '_name' => 'user_name',
                            'username' => "{{ fullname }}"]);
                            ?>" class="message-username"><b>{{ fullname }} -</b>
                                <cite style="color: black"  class="fs-12"><span class="experience">{{experience}}</span><span class="investment"> {{investment_style}}</span></cite>

                            </a>
                        </div>
                        <div class="row">
                            <div class="message-content">
                                <div class="message-body">
                                    <?= "{{{message}}}" ?>
                                </div>
                                <div class="message-tools"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 mt-5">
                    <div class="mydiv_iframe" style="display:{{status}}">
                         <a href="{{url_page}}"><img src="data:image/jpeg;base64,{{img_data}}" height="200" /></a>
                    </div>
                </div>
                </div>
                <!-- ALERT -->
                <div class="message_alert_star pr-10"></div>
                <!-- /ALERT -->
            <div class="container">
                <div class="ml-90 mt-10">
                    <div class="row">
                        <div class="col-lg-2 col-md-2 col-sm-12 mt-5">
                            <div class="message-header">
                                <div class="thumbnail float-left avatar mr-20">
                                    <a href="<?=
                                    $this->Url->build([
                                    '_name' => 'user_name',
                                    'username' => "{{ fullname_parent }}"]);
                                    ?>" >
                                        <img src="{{avatarPath_parent}}" width="60" height="60" alt="" class="user_avatar">

                                    </a>
                                </div>

                            </div>
                        </div>
                        <div class="col-lg-10 col-md-10 col-sm-12 ml-5m">
                            <div class="row">
                                <span class="">{{ date_parent }}</span>

                                <span class="mr-5 ml-10"> <?= __('Rate:') ?></span>
                                <div class="stars mb-10m" data-id="{{ message_parent_id }}" >
                                    <?= "{{{stars_parent}}}" ?>
                                </div>
                                <span class="ml-5 mr-10">
                                     {{ parent_rating }}.0
                                </span>
                            </div>
                            <div class="row">
                                <a href="<?=
                                $this->Url->build([
                                '_name' => 'user_name',
                                'username' => "{{ fullname_parent }}"]);
                                ?>" class="message-username"><b>{{ fullname_parent }} -</b>
                                    <cite style="color: black"  class="fs-12">{{experience_parent}} {{investment_style_parent}}</cite>

                                </a>
                            </div>
                            <div class="row">
                                <div class="message-content">
                                    <div class="message-body">
                                        <?= "{{{message_parent}}}";?>
                                    </div>
                                    <div class="message-tools"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 mt-5">
                    <div class="mydiv_iframe" style="display:{{status_parent}}">
                        <a href="{{url_data_parent}}"><img src="data:image/jpeg;base64,{{img_data_parent}}" height="200" />  </a>
                    </div>
                </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </li>
</script>

<script id="watchlist-option"  type="text/template">
    <option value="{{id}}" selected>{{name}}</option>
</script>