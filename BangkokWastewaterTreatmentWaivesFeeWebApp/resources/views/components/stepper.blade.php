<style>
#stepLayout {
  background-color: #ffffff;
  padding: 20px;
  box-shadow: 0px 6px 18px rgb(0 0 0 / 20%);
  border-radius: 12px;
}
#stepLayout .form-header {
  gap: 5px;
  text-align: center;
  font-size: .95em;
}
#stepLayout .form-header .stepIndicator {
  position: relative;
  flex: 1;
  padding-bottom: 30px;
}
#stepLayout .form-header .stepIndicator.active {
  font-weight: 700;
}
#stepLayout .form-header .stepIndicator.finish {
  font-weight: 600;
  color: #009688;
}
#stepLayout .form-header .stepIndicator.reject {
  font-weight: 600;
  color: #c0666e;
}
#stepLayout .form-header .stepIndicator::before {
  content: "";
  position: absolute;
  left: 50%;
  bottom: 0;
  transform: translateX(-50%);
  z-index: 9;
  width: 20px;
  height: 20px;
  background-color: #60acec;
  border-radius: 50%;
  border: 3px solid #c9e3f8;
}
#stepLayout .form-header .stepIndicator.active::before {
  background-color: #135d9c;
  border: 3px solid #c9e3f8;
}
#stepLayout .form-header .stepIndicator.finish::before {
  background-color: #009688;
  border: 3px solid #b7e1dd;
}
#stepLayout .form-header .stepIndicator.finish::before {
  background-color: #009688;
  border: 3px solid #b7e1dd;
}
#stepLayout .form-header .stepIndicator.reject::before {
  background-color: #c0666e;
  border: 3px solid #b7e1dd;
}
#stepLayout .form-header .stepIndicator.reject::before {
  background-color: #c0666e;
  border: 3px solid #b7e1dd;
}
#stepLayout .form-header .stepIndicator::after {
  content: "";
  position: absolute;
  left: 50%;
  bottom: 8px;
  width: 100%;
  height: 3px;
  background-color: #f3f3f3;
}
#stepLayout .form-header .stepIndicator.active::after {
  background-color: #135d9c;
}
#stepLayout .form-header .stepIndicator.finish::after {
  background-color: #009688;
}
#stepLayout .form-header .stepIndicator.finish::after {
  background-color: #c0666e;
}
#stepLayout .form-header .stepIndicator:last-child:after {
  display: none;
}
</style>



<div id="stepLayout" class="mt-3">
  <div class="form-header d-flex mb-4">
    <!-- <span class="stepIndicator finish"></span> -->
    <!-- <span class="stepIndicator active"></span> -->
    <!-- <span class="stepIndicator"></span> -->

    @if ($step_display1 != '')
      <span class="stepIndicator {{ $step_action1 }}">{{ $step_display1 }}</span>
    @endif
    @if ($step_display2 != '')
      <span class="stepIndicator {{ $step_action2 }}">{{ $step_display2 }}</span>
    @endif
    @if ($step_display3 != '')
      <span class="stepIndicator {{ $step_action3 }}">{{ $step_display3 }}</span>
    @endif
    @if ($step_display4 != '')
      <span class="stepIndicator {{ $step_action4 }}">{{ $step_display4 }}</span>
    @endif
    @if ($step_display5 != '')
      <span class="stepIndicator {{ $step_action5 }}">{{ $step_display5 }}</span>
    @endif
  </div>
</div>
