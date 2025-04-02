import { Server } from "socket.io";
import { createServer } from "http";

const httpServer = createServer();
const io = new Server(httpServer, { cors: { origin: "*" } });

io.on("connection", (socket) => {
    console.log(`Cliente conectado: ${socket.id}`);
});

httpServer.listen(6001, () => {
    console.log("Servidor WebSocket rodando na porta 6001");
});
