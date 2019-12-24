@extends('master')

@section('body')
<!-- Page-Title -->
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title m-0">Make Ledger Entry</h4>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- <div class="row">
    <div class="col-12">
        <div class="card m-b-30">
            <div class="card-body">
                <div class="form-group row"><label for="example-search-input"
                        class="col-sm-2 col-form-label">Search</label>
                    <div class="col-sm-10"><input class="form-control" type="search" value="How do I shoot web"
                            id="example-search-input"></div>
                </div>
                <div class="form-group row"><label for="date" class="col-sm-2 col-form-label">Date</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="text" id="date">
                    </div>
                </div>
                <div class="form-group row"><label class="col-sm-2 col-form-label">Select</label>
                    <div class="col-sm-10">
                        <input type="radio" name="choice" value="savings" /> Savings &nbsp
                        <input type="radio" name="choice" value="obligations" /> Obligations
                    </div>
                </div>

                <div class="form-group row"><label class="col-sm-2 col-form-label">Obligations</label>
                    <div class="col-sm-10"><select class="custom-select">
                            <option selected="selected">Select one</option>
                            <option value="1">Lont Term Loan</option>
                        </select></div>
                </div>
            </div>
        </div>
    </div>
</div>          <option value="2">Short Term Loan</option>
                            <option value="3">Commodity</option>
                        </select></div>
                </div>
            </div>
        </div>
    </div>
</div> -->

<new-ledger-entry :staff="{{$staff}}"><new-ledger-entry/>

@endsection


@section('js')

@endsection

