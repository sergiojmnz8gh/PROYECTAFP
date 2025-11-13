<?php

namespace App\Helpers;

use App\Models\Alumno;
use App\Models\User;

class Adapter
{
    public static function alumnoToDTO(Alumno $alumno): array
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
            'activo' => $alumno->activo,
        ];
    }

    public static function DTOtoAlumno(array $data): Alumno
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
        $alumno->activo = $data['activo'] ?? true;
        return $alumno;
    }

    public static function AllAlumnoToDTO(array $alumnos): array
    {
        $dtos = [];
        foreach ($alumnos as $alumno) {
            $dtos[] = self::alumnoToDTO($alumno);
        }
        return $dtos;
    }

    public static function DTOtoModels(array $data): array
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
        $alumno->direccion = $data['direccion'] ?? null;
        $alumno->telefono = $data['telefono'] ?? null;
        $alumno->foto = $data['foto'] ?? null;
        $alumno->cv = $data['cv'] ?? null;
        
        return ['alumno' => $alumno, 'user' => $user];
    }

    public static function userToDTO(User $user): array
    {
        return [
            'id' => $user->id,
            'email' => $user->email,
            'rol_id' => $user->rol_id,
            'activo' => $user->activo
        ];
    }
}