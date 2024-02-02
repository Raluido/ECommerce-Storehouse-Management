<div>
    <div id="mobileMenu"><img src="{{ Storage::url('mobileMenu.png') }}" alt="" class=""></div>
    <div id="dropDown" class="d-none">
        <ul class="">
            <li class=""><a href="{{ route('main') }}" class="">Principal</a></li>
            <li class="">
                <div id="subMenuPrd">Productos</div>
            </li>
            <li class="">
                <div id="subMenuStr">Almacenes</div>
            </li>
            <li class="">
                <div id="subMenuCat">Categorías</div>
            </li>
            <li class="">
                <div id="subMenuMng">Gestión</div>
            </li>
            <li class=""><a href="{{ route('main') }}" class="">Blog</a></li>
            <li class=""><a href="{{ route('main') }}" class="">Quienes somos</a></li>
        </ul>
        <div id="dropDownPrd" class="d-none">
            <ul class="">
                <li class=""><a href="{{ route('products.createForm') }}" class="">Crear producto</a></li>
                <li class=""><a href="{{ route('products.showall') }}" class="">Mostrar todos</a></li>
            </ul>
        </div>
        <div id="dropDownCat" class="d-none">
            <ul class="">
                <li class=""><a href="{{ route('categories.createForm') }}" class="">Crear categoría</a></li>
                <li class=""><a href="{{ route('categories.showall') }}" class="">Mostrar todos</a></li>
            </ul>
        </div>
        <div id="dropDownStr" class="d-none">
            <ul class="">
                <li class=""><a href="{{ route('storehouses.createForm') }}" class="">Crear almacén</a></li>
                <li class=""><a href="{{ route('storehouses.showall') }}" class="">Mostrar todos</a></li>
            </ul>
        </div>
        <div id="dropDownMng" class="d-none">
            <ul class="">
                <li class=""><a href="{{ route('storehousesManagement.showall') }}" class="">Mostrar todo</a></li>
            </ul>
        </div>
    </div>
    <div id="desktopMenuDummie"></div>
    <div id="logo">
        <img src="{{ Storage::url('logo.jpeg') }}" alt="" class="">
    </div>
    <div id="login">
        @if(auth()->id())
        <a href="{{ route('login.logout') }}" class="">Logout</a>
        @else
        <a href="{{ route('login.show') }}" class="">Login</a>
        @endif
    </div>
</div>
<nav id="desktopMenu">
    <ul class="">
        <li class=""><a href="{{ route('main') }}" class="">Principal</a></li>
        <li class=""><a href="{{ route('products.showall') }}" class="">Productos</a></li>
        <li class=""><a href="{{ route('storehouses.showall') }}" class="">Almacenes</a></li>
        <li class=""><a href="{{ route('categories.showall') }}" class="">Categorías</a></li>
        <li class=""><a href="{{ route('main') }}" class="">Blog</a></li>
        <li class=""><a href="{{ route('main') }}" class="">Quienes somos</a></li>
    </ul>
</nav>