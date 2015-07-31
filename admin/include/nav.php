<nav class="top-bar" data-topbar role="navigation">
  <ul class="title-area">
    <li class="name">
      <h1><a href="#">My Site</a></h1>
    </li>
    <li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
  </ul>
  <section class="top-bar-section">
    <ul class="right">
      <li><a href="#">Right Button Active</a></li>
      <li class="has-dropdown">
        <a href="#">Raymund F. Cuizon</a>
        <ul class="dropdown">
          <li><a href="#">Settings</a></li>
          <li class="divider"></li>
          <li><a href="#">logout</a></li>
        </ul>
      </li>
    </ul>
    <ul class="left">
        <li class="<?=($_GET['pagenav'] == 'clist'? 'active' : '')?>"><a href="clist.php?pagenav=clist">Category</a></li>
    </ul>
  </section>
</nav>