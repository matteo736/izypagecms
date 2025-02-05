import React from 'react';

const DbSetupDescription: React.FC = () => {
  return (
    <div className="py-4 lg:pl-12 lg:w-1/2 w-full">
      <h1 className='font-bold text-3xl mb-2'>Imposta il Database per IzyPage</h1>
      <p>
        Per iniziare a utilizzare <strong>Izypage</strong>, è necessario configurare correttamente il database che ospiterà tutti i tuoi dati. Il database è essenziale per memorizzare i contenuti del tuo sito, come articoli, pagine, utenti, commenti e altre informazioni.
      </p>
      <p>
        In questa fase, dovrai fornire alcune informazioni per connettere <strong>IzyPage</strong> al tuo database. Assicurati di avere a disposizione i seguenti dettagli:
      </p>
      <ul className='list-disc ml-4 my-2'>
        <li><strong>Tipo di Database:</strong> Seleziona il tipo di database che desideri utilizzare (ad esempio, MySQL o MariaDB).</li>
        <li><strong>Host del Database:</strong> Inserisci l'indirizzo del server che ospita il database. Se il database è sullo stesso server di Izypage, puoi utilizzare "localhost".</li>
        <li><strong>Nome del Database:</strong> Fornisci un nome unico per il tuo database. Questo sarà utilizzato da Izypage per identificare il database.</li>
        <li><strong>Porta del Database:</strong> Inserisci la porta tramite cui il CMS si connette al database. La porta predefinita per MySQL è solitamente la <strong>3306</strong>.</li>
        <li><strong>Nome utente e Password:</strong> Queste sono le credenziali che permettono a Izypage di accedere al database in modo sicuro. Assicurati che siano corrette per evitare problemi di connessione.</li>
      </ul>
      <p>
        Una volta configurato il database, <strong>Izypage</strong> sarà pronto per iniziare a gestire i tuoi contenuti.
      </p>
      <p>
        Clicca su <strong>"Salva Configurazione"</strong> per completare il setup del database e proseguire con la configurazione del sito.
      </p>
    </div>
  );
};

export default DbSetupDescription;
