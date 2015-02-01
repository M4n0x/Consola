<div class="sidebar darkbg">
    <div class="sidebar-toggle visible-xs push-center" data-target=".sidebar-nav" data-toggle="collapse">
        <i class="fa fa-lg fa-navicon"></i>
    </div>

    <ul class="sidebar-nav collapse">
        <li class="<?= ($activeNav==1) ? "active" : "" ?>" >
            <a href="report">
                <i class="fa fa-dashboard"> </i>
                <span> Dashboard</span>
            </a>
        </li>
        <li class="<?= ($activeNav==2) ? "active" : "" ?>">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-bar-chart-o"> </i>
                <span>Reports</span> 
                <span class="caret push-right"></span>
            </a>
            <ul class="sidebar-submenu dropdown-menu">
                <li>
                    <a href="#">
                        <i class="fa fa-dashboard"> </i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fa fa-bar-chart-o"> </i>
                        <span>Reports</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="<?= ($activeNav==3) ? "active" : "" ?>" >
            <a href="">
                <i class="fa fa-search"> </i>
                <span>Recherche</span>
            </a>
        </li>
        <li class="<?= ($activeNav==4) ? "active" : "" ?>" >
            <a href="admin">
                <i class="fa fa-cog"> </i>
                <span>Administration</span>
            </a>
        </li>
    </ul>
</div>