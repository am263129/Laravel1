</div>
</div>
</body>
<script
  src="https://code.jquery.com/jquery-3.2.1.min.js"
  integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
  crossorigin="anonymous"></script>
  @if (! Auth::guest())
  <script src="/js/admin-js/manifest.js"></script>
  <script src="/js/admin-js/vendor.js"></script>
  <script src="{{asset('js/admin-js/app.js')}}"></script>
 @endif
</html>