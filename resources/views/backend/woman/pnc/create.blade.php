@extends('backend.woman.pnc.form')

    @section('action',route('pnc.store'))

    @section('methodField', "")

    @section ('token',old('token'))

    @section ('woman_token',old('woman_token'))

    @section ('delivery_date',old('delivery_date'))

    @section ('delivery_time',old('delivery_time'))

    @section ('mother_status',old('mother_status'))

    @section ('baby_status',old('baby_status'))

    @section ('advice',old('advice'))

    @section ('checked_by',old('checked_by'))

    @section ('hp_code',old('hp_code'))

    @php($status= old('status')) 