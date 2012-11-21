var person = function() {
   // private
   var name = "Robert";
   return {
      getName: function() {
         return name;
      },
      setName: function(newName) {
         name = newName;
      }
   }
}();
alert(person.name); // undefined
alert(person.getName());  // "Robert"
person.setName("Robert Nyman");
alert(person.getName());  // "Robert Nyman"