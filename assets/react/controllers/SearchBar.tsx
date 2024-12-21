import React, {useState, useCallback} from 'react';
import _ from 'lodash';

interface SearchBarProps {
    placeholder: string;
}

interface Topic {
    id: number;
    title: string;
    description: string;
    createdAt: string;
    updatedAt: string;
}

export default function SearchBar(props: SearchBarProps) {
    const [query, setQuery] = useState('');
    const [results, setResults] = useState<Topic[]>([]);
    const [isFocused, setIsFocused] = useState(false);

    const handleInputChange = (event: React.ChangeEvent<HTMLInputElement>) => {
        setQuery(event.target.value);

        if (event.target.value) {
            // Lancer la recherche seulement si la requête n'est pas vide
            debouncedFetchResults(event.target.value);
        } else {
            setResults([]);
        }
    };

    // Utilisation de lodash.debounce pour éviter d'envoyer des requêtes trop fréquentes
    const debouncedFetchResults = useCallback(
        _.debounce(async (searchQuery: string) => {
            try {
                const response = await fetch(`/api/topics/search?query=${encodeURIComponent(searchQuery)}`);
                if (!response.ok) {
                    throw new Error('Une erreur est survenue lors de la récupération des données');
                }
                const data: Topic[] = await response.json();
                setResults(data);
            } catch (error) {
                console.error('Erreur lors de la recherche:', error);
            }
        }, 500), // délai de 500 ms avant d'envoyer la requête
        []
    );

    const handleSearch = () => {
        console.log('Search submitted for:', query);
    };

    // Fonction pour rediriger vers la page du topic lorsqu'un résultat est cliqué
    const handleResultClick = (id: number) => {
        window.open(`/topics/${id}`, '_self');
    };

    return (
        <div className="relative">
            {/* Barre de recherche */}
            <div className="flex items-center bg-white rounded-md shadow-sm p-2">
                <input
                    type="text"
                    placeholder={props.placeholder}
                    value={query}
                    onChange={handleInputChange}
                    onFocus={() => setIsFocused(true)}
                    onBlur={() => setTimeout(() => setIsFocused(false), 150)} // Delay pour permettre le clic sur les résultats
                    className="flex-grow px-4 py-2 rounded-l-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
            </div>

            {/* Résultats de recherche */}
            {isFocused && results.length > 0 && (
                <div
                    className="absolute top-full left-0 w-full bg-white shadow-lg border border-gray-300 rounded-md z-50 max-h-64 overflow-auto"
                    style={{marginTop: '0.5rem'}}
                >
                    <ul className="divide-y divide-gray-200">
                        {results.map((result) => (
                            <li
                                key={result.id}
                                className="p-2 hover:bg-gray-100 cursor-pointer"
                                onClick={() => handleResultClick(result.id)} // Utiliser la fonction de redirection
                            >
                                <strong>{result.title}</strong>
                                <p className="text-sm text-gray-500">{result.description}</p>
                            </li>
                        ))}
                    </ul>
                </div>
            )}
        </div>
    );
}
