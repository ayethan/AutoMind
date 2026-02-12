import Dashboard from './pages/Dashboard.vue';
import Login from './pages/Login.vue';
import ProductEdit from './pages/products/Edit.vue';
import ProductCreate from './pages/products/Create.vue';
import ProductShow from './pages/products/Show.vue';
import ProductIndex from './pages/products/Index.vue';
import ProductMain from './pages/products/Main.vue';
import ServiceEdit from './pages/services/Edit.vue';
import ServiceCreate from './pages/services/Create.vue';
import ServiceIndex from './pages/services/Index.vue';
import ServiceMain from './pages/services/Main.vue';
import CustomerMain from './pages/customers/Main.vue';
import CustomerCreate from './pages/customers/Create.vue';
import CustomerIndex from './pages/customers/Index.vue';
import CustomerEdit from './pages/customers/Edit.vue';
import SaleMain from './pages/sales/Main.vue';
import SaleCreate from './pages/sales/Create.vue';
import SaleEdit from './pages/sales/Edit.vue';
import SaleIndex from './pages/sales/Index.vue';
import SaleExpenses from './pages/sales/Expenses.vue';
import SalePayments from './pages/sales/Payments.vue';
import ExpenseTypeMain from './pages/expense-types/Main.vue';
import ExpenseTypeCreate from './pages/expense-types/Create.vue';
import ExpenseTypeEdit from './pages/expense-types/Edit.vue';
import ExpenseTypeIndex from './pages/expense-types/Index.vue';
import ExpenseMain from './pages/expenses/Main.vue';
import ExpenseCreate from './pages/expenses/Create.vue';
import ExpenseEdit from './pages/expenses/Edit.vue';
import ExpenseIndex from './pages/expenses/Index.vue';
import Setting from './pages/Setting.vue';
import Profile from './pages/Profile.vue';
import PasswordChange from './pages/PasswordChange.vue';
import ResetPassword from './pages/users/ResetPassword.vue';
import UserMain from './pages/users/Main.vue';
import UserCreate from './pages/users/Create.vue';
import UserIndex from './pages/users/Index.vue';
import UserEdit from './pages/users/Edit.vue';
import SupplierMain from './pages/suppliers/Main.vue';
import SupplierCreate from './pages/suppliers/Create.vue';
import SupplierIndex from './pages/suppliers/Index.vue';
import SupplierEdit from './pages/suppliers/Edit.vue';
import CategoryMain from './pages/categories/Main.vue';
import CategoryCreate from './pages/categories/Create.vue';
import CategoryIndex from './pages/categories/Index.vue';
import CategoryEdit from './pages/categories/Edit.vue';
import SubCategoryMain from './pages/sub-categories/Main.vue';
import SubCategoryCreate from './pages/sub-categories/Create.vue';
import SubCategoryIndex from './pages/sub-categories/Index.vue';
import SubCategoryEdit from './pages/sub-categories/Edit.vue';
import CarMadeMain from './pages/car-mades/Main.vue';
import CarMadeCreate from './pages/car-mades/Create.vue';
import CarMadeIndex from './pages/car-mades/Index.vue';
import CarMadeEdit from './pages/car-mades/Edit.vue';
import CarMain from './pages/cars/Main.vue';
import CarCreate from './pages/cars/Create.vue';
import CarIndex from './pages/cars/Index.vue';
import CarEdit from './pages/cars/Edit.vue';

export const routes = [
    {
        path: '/',
        redirect: 'dashboard'
    },
    {
        path: '/login',
        component: Login,
        meta: {
            template: 'blank'
        }
    },
    {
        path: '/dashboard',
        component: Dashboard,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/products',
        component: ProductMain,
        meta: {
            requiresAuth: true
        },
        children: [
            {
                path: '/',
                component: ProductIndex
            },
            {
                path: 'create',
                component: ProductCreate
            },
            {
                path: ':id',
                component: ProductShow
            },
            {
                path: ':id/edit',
                component: ProductEdit
            }
        ]
    },
    {
        path: '/services',
        component: ServiceMain,
        meta: {
            requiresAuth: true
        },
        children: [
            {
                path:'/',
                component: ServiceIndex
            },
            {
                path: 'create',
                component: ServiceCreate
            },
            {
                path: ':id/edit',
                component: ServiceEdit
            }
        ]
    },
    {
        path: '/customers',
        component: CustomerMain,
        meta: {
            requiresAuth: true
        },
        children: [
            {
                path: '/',
                component: CustomerIndex
            },
            {
                path: 'create',
                component: CustomerCreate
            },
            {
                path: ':id/edit',
                component: CustomerEdit
            }
        ]
    }, 
    {
        path: '/sales',
        component: SaleMain,
        meta: {
            requiresAuth: true
        },
        children: [
            {
                path: '/',
                component: SaleIndex
            },
            {
                path: 'create',
                component: SaleCreate
            },
            {
                path: ':id/edit',
                component: SaleEdit
            },
            {
                path: ':id/expenses',
                component: SaleExpenses
            },
            {
                path: ':id/payments',
                component: SalePayments
            }
        ]
    },
    {
        path: '/expense-types',
        component: ExpenseTypeMain,
        meta: {
            requiresAuth: true
        },
        children: [
            {
                path: '/',
                component: ExpenseTypeIndex
            },
            {
                path: 'create',
                component: ExpenseTypeCreate
            },
            {
                path: ':id/edit',
                component: ExpenseTypeEdit
            }
        ]
    },
    {
        path: '/expenses',
        component: ExpenseMain,
        meta: {
            requiresAuth: true
        },
        children: [
            {
                path: '/',
                component: ExpenseIndex
            },
            {
                path: 'create',
                component: ExpenseCreate
            },
            {
                path: ':id/edit',
                component: ExpenseEdit
            }
        ]
    },
    {
        path: '/settings',
        component: Setting,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/profile',
        component: Profile,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/password/change',
        component: PasswordChange,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/users',
        component: UserMain,
        meta: {
            requiresAuth: true,
        },
        children: [
            {
                path: "/",
                component: UserIndex
            },
            {
                path: "create",
                component: UserCreate
            },
            {
                path: ":id/edit",
                component: UserEdit
            },
            {
                path: ":id/password/reset",
                component: ResetPassword
            }
        ]
    },
    {
        path: '/suppliers',
        component: SupplierMain,
        meta: {
            requiresAuth: true,
        },
        children: [
            {
                path: "/",
                component: SupplierIndex
            },
            {
                path: "create",
                component: SupplierCreate
            },
            {
                path: ":id/edit",
                component: SupplierEdit
            }
        ]
    },
    {
        path: '/categories',
        component: CategoryMain,
        meta: {
            requiresAuth: true,
        },
        children: [
            {
                path: "/",
                component: CategoryIndex
            },
            {
                path: "create",
                component: CategoryCreate
            },
            {
                path: ":id/edit",
                component: CategoryEdit
            }
        ]
    },
    {
        path: '/sub-categories',
        component: SubCategoryMain,
        meta: {
            requiresAuth: true,
        },
        children: [
            {
                path: "/",
                component: SubCategoryIndex
            },
            {
                path: "create",
                component: SubCategoryCreate
            },
            {
                path: ":id/edit",
                component: SubCategoryEdit
            }
        ]
    },
    {
        path: '/car-mades',
        component: CarMadeMain,
        meta: {
            requiresAuth: true,
        },
        children: [
            {
                path: "/",
                component: CarMadeIndex
            },
            {
                path: "create",
                component: CarMadeCreate
            },
            {
                path: ":id/edit",
                component: CarMadeEdit
            }
        ]
    },
    {
        path: '/cars',
        component: CarMain,
        meta: {
            requiresAuth: true,
        },
        children: [
            {
                path: "/",
                component: CarIndex
            },
            {
                path: "create",
                component: CarCreate
            },
            {
                path: ":id/edit",
                component: CarEdit
            }
        ]
    }
];