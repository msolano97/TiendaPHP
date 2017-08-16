create DATABASE TiendaPHP;

use TiendaPHP;

CREATE TABLE Productos(
	Numero_registro int AUTO_INCREMENT,
    Nombre varchar(20),
    Descripcion varchar(50),
    Costo int,
    Cantidad int,
    CONSTRAINT Pk_Producto PRIMARY KEY(Numero_registro)
);

CREATE TABLE Clientes(
	Cedula int,
    Nombre varchar(10),
    PApellido varchar(10),
    MApellido varchar(10),
    CONSTRAINT Pk_Cliente PRIMARY KEY(Cedula)
);

CREATE TABLE Usuarios(
	Correo varchar(100),
    Usuario varchar(50) UNIQUE,
    Contrasena varchar(50),
    Cliente_cedula int,
    CONSTRAINT Pk_Usuario PRIMARY KEY(Correo),
    CONSTRAINT Fk_Cliente FOREIGN KEY(Cliente_cedula) REFERENCES Clientes(Cedula)
);

CREATE TABLE Cuentas(
	Usuario_correo varchar(100),
    Proveedor varchar(20),
    CONSTRAINT Pk_Cuentas PRIMARY KEY(Usuario_correo,Proveedor),
    CONSTRAINT Fk_Usuario1 FOREIGN KEY(Usuario_correo) REFERENCES Usuarios(Correo)
);

CREATE TABLE Dispositivos(
	Mac varchar(17),
    Usuario_correo varchar(100),
    CONSTRAINT Pk_Dispositivo PRIMARY KEY(Mac,Usuario_correo),
    CONSTRAINT Fk_Usuario2 FOREIGN KEY(Usuario_correo) REFERENCES Usuarios(Correo)
);

CREATE TABLE Carritos(
	Numero_registro int AUTO_INCREMENT,
    Usuario_correo varchar(100),
    Estado boolean,
    CONSTRAINT Pk_Carrito PRIMARY KEY(Numero_registro),
    CONSTRAINT Fk_Usuario3 FOREIGN KEY(Usuario_correo) REFERENCES Usuarios(Correo)
);

CREATE TABLE DetalleCarritos(
	Numero_registro_carrito int,
    Numero_registro_producto int,
    CONSTRAINT Pk_DetalleCarrito PRIMARY KEY(Numero_registro_carrito,Numero_registro_producto),
    CONSTRAINT Fk_Carrito FOREIGN KEY(Numero_registro_carrito) REFERENCES Carritos(Numero_registro),
    CONSTRAINT Fk_Producto FOREIGN KEY(Numero_registro_producto) REFERENCES Productos(Numero_registro)
);