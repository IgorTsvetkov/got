export function updateModelInArrayAll(arr_old,arr_new){
    arr_new.forEach(new_model=>updateModelInArray(arr_old,new_model));
    return arr_old;
}
export function updateModelInArray(arr_old,new_model) {
  let old_model = arr_old.find(old_model => +old_model["id"] === +new_model["id"]);
  return updateModel(old_model,new_model);
}
//Обновляет только те поля которые существуют в new_model
export function updateModel(old_model,new_model) {
  Object.keys(new_model).forEach(function (key) {
    if (key === "id")
      return;
    old_model[key] = new_model[key];
  });
  return old_model;
}