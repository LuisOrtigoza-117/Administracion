# Sistema de Administración Escolar - Laboratorio de Informática

## 1. Project Overview

- **Nombre del proyecto**: SchoolAdmin - Sistema de Administración Escolar para Laboratorio de Informática
- **Tipo**: Aplicación web (Laravel + Blade)
- **Funcionalidad principal**: Sistema integral para gestionar un laboratorio de informática escolar con control de asistencia, reportes de equipos, seguimiento de tareas y organización por grupos
- **Usuarios objetivo**: Profesores y administradores del laboratorio de informática

## 2. UI/UX Specification

### Layout Structure

- **Header**: Logo del sistema, navegación principal, usuario activo
- **Sidebar**: Menú lateral con acceso a todas las secciones
- **Content Area**: Contenido principal dinámico
- **Footer**: Información de copyright y versión

### Responsive Breakpoints
- Mobile: < 768px
- Tablet: 768px - 1024px
- Desktop: > 1024px

### Visual Design

#### Color Palette
- Primary: #1e3a5f (Azul oscuro institucional)
- Secondary: #3498db (Azul claro)
- Accent: #27ae60 (Verde éxito)
- Warning: #f39c12 (Naranja)
- Danger: #e74c3c (Rojo)
- Background: #f5f7fa (Gris claro)
- Card: #ffffff (Blanco)
- Text: #2c3e50 (Gris oscuro)
- Text Light: #7f8c8d (Gris medio)

#### Typography
- Font Family: 'Segoe UI', sans-serif
- Headings: 700 weight
- Body: 400 weight
- H1: 28px, H2: 24px, H3: 20px, H4: 16px

#### Spacing
- Base unit: 8px
- Small: 8px, Medium: 16px, Large: 24px, XLarge: 32px

### Components

#### Cards
- Background: white
- Border-radius: 8px
- Shadow: 0 2px 8px rgba(0,0,0,0.1)
- Padding: 20px

#### Buttons
- Primary: #3498db background, white text
- Success: #27ae60 background
- Warning: #f39c12 background
- Danger: #e74c3c background
- Border-radius: 4px
- Padding: 10px 20px

#### Tables
- Striped rows
- Hover effect
- Header: #1e3a5f background, white text

## 3. Functionality Specification

### Módulo 1: Gestión de Grupos
- Crear, editar, eliminar grupos (grado, grupo, ciclo escolar)
- Asignar estudiantes a grupos
- Ver lista de grupos con información de estudiantes
- Ver historial de grupos anteriores

### Módulo 2: Gestión de Estudiantes
- Registro de estudiantes (nombre, apellido, número de control,email)
- Asignación a grupos
- Historial de asistencia
- Perfil individual del estudiante

### Módulo 3: Pase de Lista (Asistencia)
- Tomar asistencia por grupo y fecha
- Estados: Presente, Ausente, Retardo, Justificación
- Registro de hora de llegada
- Reportes de asistencia por grupo y fecha
- Estadísticas de asistencia por estudiante

### Módulo 4: Reporte de Equipos Dañados
- Registro de equipos (número de PC, ubicación, estado)
- Reportar daño (equipo, descripción, fecha, reporta)
- Estados: Funcional, Dañado, En Reparación, Dados de Baja
- Historial de daños por equipo
- Bitácora de mantenimiento

### Módulo 5: Seguimiento de Tareas/Actividades
- Crear tareas por grupo (título, descripción, fecha entrega, puntos)
- Adjuntar archivos (opcional)
- Entrega de tareas por estudiantes
- Calificación de entregas
- Estados: Pendiente, Entregado, Calificado
- Reporte de tareas por grupo

### Dashboard
- Resumen de grupos activos
- Estudiantes totles
- Equipos con problemas
- Tareas pendientes
- Asistencia del día

## 4. Data Models

### Group
- id, name, grade, section, school_year, created_at, updated_at

### Student
- id, name, lastname, student_number, email, group_id, created_at, updated_at

### Attendance
- id, student_id, group_id, date, status (present, absent, late, justified), arrival_time, created_at

### Computer
- id, pc_number, location, status (functional, damaged, repairing, retired), created_at, updated_at

### ComputerReport
- id, computer_id, description, reported_by, report_date, resolved_date, status, created_at, updated_at

### Task
- id, group_id, title, description, due_date, max_points, created_at, updated_at

### TaskSubmission
- id, task_id, student_id, file_path, submitted_at, grade, feedback, created_at, updated_at

## 5. Acceptance Criteria

- [ ] El sistema permite crear y gestionar grupos escolares
- [ ] Se pueden registrar estudiantes y asignarlos a grupos
- [ ] El pase de lista funciona con todos los estados (presente, ausente, retardo, justificación)
- [ ] Los reportes de equipos permiten registrar daños y seguimiento
- [ ] Las tareas pueden crearse, entregarse y calificarse
- [ ] El dashboard muestra estadísticas relevantes
- [ ] La interfaz es responsiva y funcional en dispositivos comunes
- [ ] El sistema permite exportar/visualizar reportes de asistencia y tareas
