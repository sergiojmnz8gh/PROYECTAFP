<?php

namespace App\Helpers;

use App\Models\Alumno;
use App\Models\User;

class Adapter {
    public static function alumnoToDTO($alumno)
    {
        return [
            'id' => $alumno->id,
            'user_id' => $alumno->user_id,
            'nombre' => $alumno->nombre,
            'apellidos' => $alumno->apellidos,
            'email' => $alumno->email,
            'telefono' => $alumno->telefono,
            'direccion' => $alumno->direccion,
            'foto' => $alumno->foto,
            'cv' => $alumno->cv,
            'ciclo' => $alumno->ciclo_id,
            'activo' => $alumno->activo,
        ];
    }

    public static function DTOtoAlumno($data)
    {
        $alumno = new Alumno();
        $alumno->id = $data['id'] ?? null;
        $alumno->user_id = $data['user_id'] ?? null;
        $alumno->nombre = $data['nombre'] ?? '';
        $alumno->apellidos = $data['apellidos'] ?? '';
        $alumno->email = $data['email'] ?? '';
        $alumno->telefono = $data['telefono'] ?? null;
        $alumno->direccion = $data['direccion'] ?? null;
        $alumno->foto = $data['foto'] ?? null;
        $alumno->cv = $data['cv'] ?? null;
        $alumno->ciclo_id = $data['ciclo'] ?? null;
        $alumno->activo = $data['activo'] ?? true;
        return $alumno;
    }

    public static function AllAlumnoToDTO($alumnos)
    {
        $dtos = [];
        foreach ($alumnos as $alumno) {
            $dtos[] = self::alumnoToDTO($alumno);
        }
        return $dtos;
    }

    public static function solicitudToDTO($solicitud) {
        return [
            'id' => $solicitud->id,
            'oferta_titulo' => $solicitud->oferta_titulo,
            'alumno_nombre' => $solicitud->alumno_nombre,
            'alumno_apellidos' => $solicitud->alumno_apellidos,
            'fecha_solicitud' => $solicitud->fecha_solicitud,
            'cv_visto' => $solicitud->cv_visto,
        ];
    }

    public static function AllSolicitudToDTO($solicitudes) {
        $dtos = [];
        foreach ($solicitudes as $solicitud) {
            $dtos[] = self::solicitudToDTO($solicitud);
        }
        return $dtos;
    }

    public static function DTOtoEmpresa($empresa, $data) {
    $empresa->id = $data['id'] ?? $empresa->id;
    $empresa->nombre = $data['nombre'] ?? $empresa->nombre;
    $empresa->email = $data['email'] ?? $empresa->email;
    $empresa->telefono = $data['telefono'] ?? $empresa->telefono;
    $empresa->direccion = $data['direccion'] ?? $empresa->direccion;
    $empresa->logo = $data['logo'] ?? $empresa->logo;
    $empresa->activo = $data['activo'] ?? $empresa->activo;

    return $empresa;
    }

    public static function DTOtoOferta($oferta, $data) {
        $oferta->id = $data['id'] ?? $oferta->id;
        $oferta->titulo = $data['titulo'] ?? $oferta->titulo;
        $oferta->descripcion = $data['descripcion'] ?? $oferta->descripcion;
        $oferta->fecha_inicio = $data['fecha_inicio'] ?? $oferta->fecha_inicio;
        $oferta->fecha_fin = $data['fecha_fin'] ?? $oferta->fecha_fin;
        $oferta->empresa_id = $data['empresa_id'] ?? $oferta->empresa_id;
        $oferta->ciclo_id = $data['ciclo_id'] ?? $oferta->ciclo_id;
        return $oferta;
    }

    public static function DTOtoModels($data)
    {
        $user = new User();
        $user->id = $data['user_id'] ?? null;
        $user->email = $data['email'] ?? '';
        $user->rol_id = $data['rol_id'] ?? 2; 
        $user->activo = $data['activo'] ?? true;

        $alumno = new Alumno();
        $alumno->id = $data['id'] ?? null;
        $alumno->nombre = $data['nombre'] ?? '';
        $alumno->apellidos = $data['apellidos'] ?? '';
        $alumno->telefono = $data['telefono'] ?? null;
        $alumno->direccion = $data['direccion'] ?? null;
        $alumno->foto = $data['foto'] ?? null;
        $alumno->cv = $data['cv'] ?? null;
        $alumno->ciclo_id = $data['ciclo'] ?? null;
        
        return ['alumno' => $alumno, 'user' => $user];
    }

    public static function userToDTO($user)
    {
        return [
            'id' => $user->id,
            'email' => $user->email,
            'rol_id' => $user->rol_id,
            'activo' => $user->activo
        ];
    }
}