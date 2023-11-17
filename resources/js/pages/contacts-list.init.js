/*
Template Name: SIGTG - SISTEMA INFORMÁTICO PARA LA GESTIÓN DE TRABAJOS DE GRADUACIÓN
Author: Themesbrand
Website: https://Themesbrand.com/
Contact: Themesbrand@gmail.com
File: Ecommerce order Js File
*/

// Basic Table
new gridjs.Grid({
  columns:
    [
      {
        name: '#',
        sort: {
          enabled: false
      },
        formatter: (function (cell) {
          return gridjs.html('<div class="form-check font-size-16"><input class="form-check-input" type="checkbox" id="orderidcheck01"><label class="form-check-label" for="orderidcheck01"></label></div>');
        })
      },
      {
        name: 'Name',
        formatter: (function (cell) {
          return gridjs.html('<img src="assets/images/users/'+ cell[0]+'" alt="" class="avatar-sm rounded-circle me-2" /><a href="#" class="text-body">' + cell[1] + "</a>");
        })
      },

      "Position", "Email",
      {
        name: "View Details",
        sort: {
          enabled: false
      },
        formatter: (function (cell) {
          return gridjs.html('<ul class="list-inline mb-0">' +
            '<li class="list-inline-item">' +
            '<a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit" class="px-2 text-primary"><i class="bx bx-pencil font-size-18"></i></a>' +
            ' </li>' +
            ' <li class="list-inline-item">' +
            ' <a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete" class="px-2 text-danger"><i class="bx bx-trash-alt font-size-18"></i></a>' +
            '</li>' +
            ' <li class="list-inline-item dropdown">' +
            '<a class="text-muted dropdown-toggle font-size-18 px-2" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true">' +
            ' <i class="bx bx-dots-vertical-rounded"></i>' +
            ' </a>' +
            ' <div class="dropdown-menu dropdown-menu-end">' +
            ' <a class="dropdown-item" href="#">Action</a>' +
            '  <a class="dropdown-item" href="#">Another action</a>' +
            '   <a class="dropdown-item" href="#">Something else here</a>' +
            ' </div>' +
            ' </li>' +
            ' </ul>');
        })
      }
    ],
  pagination: {
    limit: 10
  },
  sort: true,
  search: true,
  data: [
    ["", ["avatar-1.jpg", "Simon Ryles"], "Full Stack Developer", "SimonRyles@SIGTG - FIA.com", "$400", "Paid", "Mastercard", "View Details"],
    ["", ["avatar-2.jpg", "Marion Walker"], "Frontend Developer", "MarionWalker@SIGTG - FIA.com", "$452", "Chargeback", "Visa", "View Details"],
    ["", ["avatar-3.jpg", " Marion Walker"], "UI/UX Designer", "FrederickWhite@SIGTG - FIA.com", "$632", "Refund", "Paypal", "View Details"],
    ["", ["avatar-4.jpg", " Shanon Marvin"], "Backend Developer", "ShanonMarvin@SIGTG - FIA.com", "$521", "Refund", "COD", "View Details"],
    ["", ["avatar-5.jpg", "Mark Jones"], "Frontend Developer", "MarkJones@SIGTG - FIA.com", "$521", "Paid", "Mastercard", "View Details"],
    ["", ["avatar-6.jpg", " Janice Morgan"], "Backend Developer", "JaniceMorgan@SIGTG - FIA.com", "$365", "Chargeback", "Visa", "View Details"],
    ["", ["avatar-7.jpg", "Patrick Petty"], "UI/UX Designer", "PatrickPetty@SIGTG - FIA.com", "$452", "Paid", "Paypal", "View Details"],
    ["", ["avatar-8.jpg", " Marilyn Horton"], "Backend Developer", "MarilynHorton@SIGTG - FIA.com", "$365", "Paid", "COD", "View Details"],
    ["", ["avatar-9.jpg", "Neal Womack"], "Full Stack Developer", "NealWomack@SIGTG - FIA.com", "$254", "Refund", "COD", "View Details"],
    ["", ["avatar-10.jpg", "Steven Williams"], "Frontend Developer", "StevenWilliams@SIGTG - FIA.com", "$400", "Paid", "Mastercard", "View Details"],
    ["", ["avatar-1.jpg", "Stacie Parker"], "Full Stack Developer", "StacieParker@SIGTG - FIA.com", "$400", "Paid", "Mastercard", "View Details"],
    ["", ["avatar-2.jpg", "Betty Wilson"], "Frontend Developer", "BettyWilson@SIGTG - FIA.com", "$452", "Chargeback", "Visa", "View Details"],
    ["", ["avatar-3.jpg", "Roman Crabtree"], "UI/UX Designer", "RomanCrabtree@SIGTG - FIA.com", "$632", "Refund", "Paypal", "View Details"],
    ["", ["avatar-4.jpg", "Marisela Butler"], "Backend Developer", "MariselaButler@SIGTG - FIA.com", "$521", "Refund", "COD", "View Details"],
    ["", ["avatar-5.jpg", "Roger Slayton"], "Frontend Developer", "RogerSlayton@SIGTG - FIA.com", "$521", "Paid", "Mastercard", "View Details"],
    ["", ["avatar-6.jpg", "Barbara Torres"], "Backend Developer", "BarbaraTorres@SIGTG - FIA.com", "$365", "Chargeback", "Visa", "View Details"],
    ["", ["avatar-7.jpg", "Daniel Rigney"], "UI/UX Designer", "DanielRigney@SIGTG - FIA.com", "$452", "Paid", "Paypal", "View Details"],
    ["", ["avatar-8.jpg", "Kenneth Linck"], "Backend Developer", "KennethLinck@SIGTG - FIA.com", "$365", "Paid", "COD", "View Details"],
    ["", ["avatar-9.jpg", "Felix Perry"], "Full Stack Developer", "FelixPerry@SIGTG - FIA.com", "$254", "Refund", "COD", "View Details"],
    ["", ["avatar-10.jpg", "Willie Verner"], "Frontend Developer", "WillieVerner@SIGTG - FIA.com", "$400", "Paid", "Mastercard", "View Details"],
  ]
}).render(document.getElementById("table-contacts-list"));

flatpickr('#order-date', {
  maxDate: "today"
});
