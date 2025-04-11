import { Server } from "socket.io";
import { createServer } from "http";

const httpServer = createServer();
const io = new Server(httpServer, {
    cors: { origin: "*" },  // Permitindo qualquer origem
});

io.on("connection", (socket) => {
    console.log(`🧠 Cliente conectado: ${socket.id}`);

    // Estudante entra na sala do motorista
    socket.on("entrarSala", (motoristaId) => {
        socket.join(`motorista_${motoristaId}`);
        console.log(`🔔 Cliente ${socket.id} entrou na sala motorista_${motoristaId}`);
    });

    // Motorista envia localização
    socket.on("localizacao", (data) => {
        const { latitude, longitude, motorista_id, viagem_id } = data;
        console.log("📍 Localização recebida:", data);

        // Reenvia a localização apenas para a sala do motorista correspondente
        io.to(`motorista_${motorista_id}`).emit("atualizacao-localizacao", {
            latitude,
            longitude,
            viagem_id,  // Incluindo ID da viagem para rastreamento mais detalhado
        });
    });

    socket.on("disconnect", () => {
        console.log(`❌ Cliente desconectado: ${socket.id}`);
    });
});

httpServer.listen(6001, () => {
    console.log("🚀 Servidor WebSocket rodando na porta 6001");
});
