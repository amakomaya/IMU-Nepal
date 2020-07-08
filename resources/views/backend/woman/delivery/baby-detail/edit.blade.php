@extends('baby-detail.form')

    @section('action',route('baby-detail.update', $data->id))

    @section('methodField',method_field('PUT'))

    @section ('token',!!old('token')? old('token') : $data->token)

    @section ('delivery_token',!!old('delivery_token')? old('delivery_token') : $data->delivery_token)

    @section ('gender',!!old('gender')? old('gender') : $data->gender)

    @section ('weight',!!old('weight')? old('weight') : $data->weight)

    @section ('premature_birth',!!old('premature_birth')? old('premature_birth') : $data->premature_birth)

    @section ('baby_alive',!!old('baby_alive')? old('baby_alive') : $data->baby_alive)

    @section ('baby_status',!!old('baby_status')? old('baby_status') : $data->baby_status)

    @section ('advice',!!old('advice')? old('advice') : $data->advice)