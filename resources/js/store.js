import authModule from "./store-modules/authModule";
import productModule from "./store-modules/productModule";
import serviceModule from "./store-modules/serviceModule";
import customerModule from "./store-modules/customerModule";
import saleModule from "./store-modules/saleModule";
import expenseModule from "./store-modules/expenseModule";
import expenseTypeModule from "./store-modules/expenseTypeModule";
import userModule from "./store-modules/userModule";
import categoryModule from "./store-modules/categoryModule";
import subCategoryModule from "./store-modules/subCategoryModule";
import supplierModule from "./store-modules/supplierModule";
import carMadeModule from "./store-modules/carMadeModule";
import carModule from "./store-modules/carModule";

export default {
  modules: {
    auth: authModule,
    product: productModule,
    service: serviceModule,
    customer: customerModule,
    sale: saleModule,
    expense: expenseModule,
    expense_type: expenseTypeModule,
    user: userModule,
    category: categoryModule,
    sub_category: subCategoryModule,
    supplier: supplierModule,
    car_made: carMadeModule,
    car: carModule
  }
} 