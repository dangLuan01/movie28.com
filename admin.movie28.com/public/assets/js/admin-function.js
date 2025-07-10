function generateSlug(str) {
    str = str.toLowerCase();
    str = str.replace(/đ/g, "d");
    str = str.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
    str = str
      .replace(/[^a-z0-9\s-]/g, "")
      .replace(/\s+/g, "-")
      .replace(/-+/g, "-");
  
    return str;
}
$(document).ready(function () {
    $(document).on("input", ".number_format", function () {
      $(this).val(formatNumber($(this).val()));
    });
    $("#name").on("input", function () {
      var title = $(this).val();
      var slug = generateSlug(title);
      $("#slug").val(slug);
    });
});