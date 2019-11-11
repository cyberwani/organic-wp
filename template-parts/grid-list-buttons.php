<div id="GridList" class="uk-width-auto" uk-grid>
  <div class="uk-width-auto">
    <div class="uk-inline">
      <div class="uk-background-primary uk-light" style="padding:5px;">
        <a href="<?php echo esc_url( add_query_arg( 'grid_list', 'grid-view', '' ) ); ?>" class="grid-toggle"><span uk-icon="grid"></span></a>
      </div>
    </div>
    <div class="uk-inline">
      <div class="uk-background-primary uk-light" style="padding:5px;">
        <a href="<?php echo esc_url( add_query_arg( 'grid_list', 'list-view', '' ) ); ?>" class="list-toggle"><span uk-icon="icon: table; ratio: 1.1"></span></a>
      </div>
    </div>
  </div>
</div>