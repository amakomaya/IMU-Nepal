@extends('backend.woman.lab-test.form')

    @section('action',route('lab-test.store'))

    @section('methodField', "")

    @section ('token',old('token'))

    @section ('date',old('date'))

    @section ('woman_token',old('woman_token'))

    @section ('urine_protin',old('urine_protin'))

    @section ('urine_sugar',old('urine_sugar'))

    @section ('blood_sugar',old('blood_sugar'))

    @section ('hbsag',old('hbsag'))

    @section ('vdrl',old('vdrl'))

    @section ('retro_virus',old('retro_virus'))

    @section ('other',old('other'))