<template>
    <ul class="sidebar navbar-nav">
        <li class="nav-item">
            <router-link class="nav-link" to="/dashboard">
                <i class="fas fa-fw fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </router-link>
        </li>

        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="salesDropDown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-fw fa-shopping-cart"></i>
                <span>Sales</span>
            </a>
            <div class="dropdown-menu" aria-labelledby="salesDropDown">
                <router-link to="/sales" class="dropdown-item">List</router-link>
                <div class="dropdown-divider"></div>
                <router-link to="/sales/create" class="dropdown-item">Create Sales</router-link>
            </div>
        </li>

        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="productsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-fw fa-boxes"></i>
                <span>Products</span>
            </a>
            <div class="dropdown-menu" aria-labelledby="productsDropdown">
                <router-link to="/products" class="dropdown-item">List</router-link>
                <div class="dropdown-divider"></div>
                <router-link to="/products/create" class="dropdown-item">Create Product</router-link>
            </div>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="customersDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-fw fa-user"></i>
                <span>Customers</span>
            </a>
            <div class="dropdown-menu" aria-labelledby="customersDropdown">
                <router-link to="/customers" class="dropdown-item">List</router-link>
                <div class="dropdown-divider"></div>
                <router-link to="/customers/create" class="dropdown-item">Create Customer</router-link>
            </div>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="carsDropDown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-fw fa-car"></i>
                <span>Cars</span>
            </a>
            <div class="dropdown-menu" aria-labelledby="carsDropDown">
                <router-link to="/cars" class="dropdown-item">List</router-link>
                <div class="dropdown-divider"></div>
                <router-link to="/cars/create" class="dropdown-item">Create Car</router-link>
            </div>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="suppliersDropDown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-fw fa-people-carry"></i>
                <span>Suppliers</span>
            </a>
            <div class="dropdown-menu" aria-labelledby="suppliersDropDown">
                <router-link to="/suppliers" class="dropdown-item">List</router-link>
                <div class="dropdown-divider"></div>
                <router-link to="/suppliers/create" class="dropdown-item">Create Supplier</router-link>
            </div>
        </li>

        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="expensesDropDown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-fw fa-money-bill-wave"></i>
                <span>Expenses</span>
            </a>
            <div class="dropdown-menu" aria-labelledby="expensesDropDown">
                <router-link to="/expenses" class="dropdown-item">List</router-link>
                <div class="dropdown-divider"></div>
                <router-link to="/expenses/create" class="dropdown-item">Create Expenses</router-link>
            </div>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="userDropDown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-fw fa-users"></i>
                <span>Users</span>
            </a>
            <div class="dropdown-menu" aria-labelledby="userDropDown">
                <router-link to="/users" class="dropdown-item">List</router-link>
                <div class="dropdown-divider"></div>
                <router-link to="/users/create" class="dropdown-item">Create User</router-link>
            </div>
        </li>

        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="userDropDown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-wrench"></i>
                <span>Set Up</span>
            </a>
            <div class="dropdown-menu" aria-labelledby="userDropDown">
                <router-link to="/car-mades" class="dropdown-item">Car Mades</router-link>
                <div class="dropdown-divider"></div>
                <router-link to="/categories" class="dropdown-item">Categories</router-link>
                <div class="dropdown-divider"></div>
                <router-link to="/expense-types" class="dropdown-item">Expense Types</router-link>
                <div class="dropdown-divider"></div>
                <router-link to="/sub-categories" class="dropdown-item">Sub Categories</router-link>

            </div>
        </li>

        <li class="nav-item">
            <router-link class="nav-link" to="/settings">
                <i class="fas fa-sliders-h fa-fw"></i>
                <span>Setting</span>
            </router-link>
        </li>
    </ul>
</template>


<script>
    export default {
        name: 'SideBar',
    }
</script>
