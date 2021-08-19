<li>
    <a href="#">
        <i class="fa fa-desktop" aria-hidden="true"></i>

        Monitoring <span class="fa arrow"></span>
    </a>

    <ul class="nav nav-second-level">
      @if(auth()->user()->role == 'province')
        <li>
              <a href="{{ route('report.visualization') }}">
                  <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                  Lab Visualizaion
                  <span class="label label-success pull-right">LV</span>
              </a>
          </li>
          {{-- <li>
              <a href="{{ route('report.regdev') }}">
                  <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                  Registered Device
                  <span class="label label-warning pull-right">RD</span>
              </a>
          </li> --}}
        @endif
        <li>
            <a href="{{ route('report.poe') }}">
                <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                POE
            </a>
        </li>
    </ul>
</li>