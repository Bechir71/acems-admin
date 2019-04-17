--
-- PostgreSQL database dump
--

-- Dumped from database version 11.2
-- Dumped by pg_dump version 11.2

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: address; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.address (
    id integer NOT NULL,
    name character varying(255) NOT NULL
);


--
-- Name: address_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.address_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: app_user; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.app_user (
    id integer NOT NULL,
    ufr_id integer,
    level_id integer,
    address_id integer,
    post_id integer,
    username character varying(180) NOT NULL,
    username_canonical character varying(255) NOT NULL,
    email character varying(255),
    email_canonical character varying(255),
    enabled boolean NOT NULL,
    salt character varying(255) DEFAULT NULL::character varying,
    password character varying(255),
    last_login timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    confirmation_token character varying(180) DEFAULT NULL::character varying,
    password_requested_at timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    roles text NOT NULL,
    phone character varying(255) DEFAULT NULL::character varying,
    room character varying(255),
    membership_fee boolean,
    gender_id integer
);


--
-- Name: COLUMN app_user.roles; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.app_user.roles IS '(DC2Type:array)';


--
-- Name: app_user_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.app_user_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: gender; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.gender (
    id integer NOT NULL,
    value character varying(255) NOT NULL
);


--
-- Name: gender_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.gender_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: level; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.level (
    id integer NOT NULL,
    name character varying(255) NOT NULL
);


--
-- Name: level_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.level_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: post; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.post (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    description character varying(255) DEFAULT NULL::character varying
);


--
-- Name: post_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.post_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: ufr; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.ufr (
    id integer NOT NULL,
    name character varying(255) NOT NULL
);


--
-- Name: ufr_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.ufr_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Data for Name: address; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.address (id, name) FROM stdin;
64	Village A
65	Village B
66	Village C
67	Village D
68	Village E
69	Village F
70	Village G
71	Village H
72	Village I
73	Village J
74	Village K
75	Village L
76	Village M
77	Village N
78	Village O
79	Sanar
80	Ngallel
81	Boudiouk
82	Ndar (Ville)
\.


--
-- Data for Name: app_user; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.app_user (id, ufr_id, level_id, address_id, post_id, username, username_canonical, email, email_canonical, enabled, salt, password, last_login, confirmation_token, password_requested_at, roles, phone, room, membership_fee, gender_id) FROM stdin;
23	42	30	77	103	Asse Malik Aliou Niang	asse malik aliou niang	\N	\N	f	\N	\N	\N	\N	\N	a:0:{}	776690968	11	\N	\N
18	40	28	77	97	Diallo Ahmed Tidiani	diallo ahmed tidiani	\N	\N	f	\N	\N	\N	\N	\N	a:0:{}	781700807	11	\N	\N
22	40	27	77	111	Bechir Ba	bechir ba	\N	\N	f	\N	\N	\N	\N	\N	a:0:{}	783742671	11	\N	\N
21	37	29	79	95	Moustapha El Haj	moustapha el haj	\N	\N	f	\N	\N	\N	\N	\N	a:0:{}	26525817	\N	\N	\N
25	42	26	71	\N	Mouhamed Ahmed	mouhamed ahmed	\N	\N	f	\N	\N	\N	\N	\N	a:0:{}	764376389	123	\N	\N
26	42	30	71	\N	Daouda Dia	daouda dia	\N	\N	f	\N	\N	\N	\N	\N	a:0:{}	\N	168	\N	\N
27	\N	\N	72	\N	Mouhamed Lemine	mouhamed lemine	\N	\N	f	\N	\N	\N	\N	\N	a:0:{}	\N	104	\N	\N
28	40	27	76	106	Diafra Soumaré	diafra soumaré	\N	\N	f	\N	\N	\N	\N	\N	a:0:{}	778561710	29	\N	\N
29	42	28	68	\N	Diariatou Diagana	diariatou diagana	\N	\N	f	\N	\N	\N	\N	\N	a:0:{}	\N	16G7	\N	\N
30	\N	\N	67	\N	Mouhamedou	mouhamedou	\N	\N	f	\N	\N	\N	\N	\N	a:0:{}	\N	6G6	\N	\N
31	42	30	68	\N	Khadijatou Diagana	khadijatou diagana	\N	\N	f	\N	\N	\N	\N	\N	a:0:{}	\N	16G7	\N	\N
32	40	27	71	96	Salass	salass	\N	\N	f	\N	\N	\N	\N	\N	a:0:{}	26525817	116	\N	\N
17	43	29	79	\N	Admin	admin	acems-admin@gmail.com	acems-admin@gmail.com	t	\N	$2y$13$Uk23HLodzAj3pyUbrgNqi./D0B/X6YSo6WWu.F/AIJPNbdn.7m93W	2019-04-17 00:28:42	\N	\N	a:1:{i:0;s:16:"ROLE_SUPER_ADMIN";}	\N	\N	\N	\N
\.


--
-- Data for Name: gender; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.gender (id, value) FROM stdin;
1	Masculin
2	Féminin
\.


--
-- Data for Name: level; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.level (id, name) FROM stdin;
26	Licence 1
27	Licence 2
28	Licence 3
29	Master 1
30	Master 2
31	Doctorat
\.


--
-- Data for Name: post; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.post (id, name, description) FROM stdin;
95	Président	\N
96	Vice Président	\N
97	Secrétaire Général·e	\N
98	Secrétaire Général adjoint·e	\N
99	Trésorier·e Général·e	\N
100	Trésorier·e Général·e adjoint·e	\N
101	Commissaire au compte	\N
102	Commissaire au compte adjoint·e	\N
103	Chargé·e de la sportive	\N
104	Chargé·e de la sportive adjoint·e	\N
105	Chargé·e de la communication	\N
106	Chargé·e de la communication adjoint·e	\N
107	Chargé·e de la commission féminine	\N
108	Chargé·e de la commission féminine adjoint·e	\N
109	Chargé·e des relations extérieures	\N
110	Chargé·e des relations extérieures adjoint·e	\N
111	Chargé·e de la commission d'innovation	\N
112	Chargé·e de la commission d'innovation adjoint·e	\N
113	Chargé·e de la commission d'organisation	\N
114	Chargé·e de la commission d'organisation adjoint·e	\N
115	Chargé·e de la commission sociale et culturelle	\N
116	Chargé·e de la commission sociale et culturelle adjoint·e	\N
117	Chargé·e de la commission pédagogique et scientifique	\N
118	Chargé·e de la commission pédagogique et scientifique adjoint·e	\N
\.


--
-- Data for Name: ufr; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.ufr (id, name) FROM stdin;
35	2S
36	CRAC
37	IPSL
38	LSH
39	S2ATA
40	SAT
41	SEFS
42	SEG
43	SJP
\.


--
-- Name: address_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.address_id_seq', 82, true);


--
-- Name: app_user_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.app_user_id_seq', 32, true);


--
-- Name: gender_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.gender_id_seq', 2, true);


--
-- Name: level_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.level_id_seq', 31, true);


--
-- Name: post_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.post_id_seq', 118, true);


--
-- Name: ufr_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.ufr_id_seq', 43, true);


--
-- Name: address address_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.address
    ADD CONSTRAINT address_pkey PRIMARY KEY (id);


--
-- Name: app_user app_user_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.app_user
    ADD CONSTRAINT app_user_pkey PRIMARY KEY (id);


--
-- Name: gender gender_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gender
    ADD CONSTRAINT gender_pkey PRIMARY KEY (id);


--
-- Name: level level_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.level
    ADD CONSTRAINT level_pkey PRIMARY KEY (id);


--
-- Name: post post_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.post
    ADD CONSTRAINT post_pkey PRIMARY KEY (id);


--
-- Name: ufr ufr_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ufr
    ADD CONSTRAINT ufr_pkey PRIMARY KEY (id);


--
-- Name: idx_88bdf3e95fb14ba7; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_88bdf3e95fb14ba7 ON public.app_user USING btree (level_id);


--
-- Name: idx_88bdf3e9708a0e0; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_88bdf3e9708a0e0 ON public.app_user USING btree (gender_id);


--
-- Name: idx_88bdf3e9a469cb09; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_88bdf3e9a469cb09 ON public.app_user USING btree (ufr_id);


--
-- Name: idx_88bdf3e9f5b7af75; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_88bdf3e9f5b7af75 ON public.app_user USING btree (address_id);


--
-- Name: uniq_88bdf3e94b89032c; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX uniq_88bdf3e94b89032c ON public.app_user USING btree (post_id);


--
-- Name: uniq_88bdf3e9c05fb297; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX uniq_88bdf3e9c05fb297 ON public.app_user USING btree (confirmation_token);


--
-- Name: app_user fk_88bdf3e94b89032c; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.app_user
    ADD CONSTRAINT fk_88bdf3e94b89032c FOREIGN KEY (post_id) REFERENCES public.post(id);


--
-- Name: app_user fk_88bdf3e95fb14ba7; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.app_user
    ADD CONSTRAINT fk_88bdf3e95fb14ba7 FOREIGN KEY (level_id) REFERENCES public.level(id);


--
-- Name: app_user fk_88bdf3e9708a0e0; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.app_user
    ADD CONSTRAINT fk_88bdf3e9708a0e0 FOREIGN KEY (gender_id) REFERENCES public.gender(id);


--
-- Name: app_user fk_88bdf3e9a469cb09; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.app_user
    ADD CONSTRAINT fk_88bdf3e9a469cb09 FOREIGN KEY (ufr_id) REFERENCES public.ufr(id);


--
-- Name: app_user fk_88bdf3e9f5b7af75; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.app_user
    ADD CONSTRAINT fk_88bdf3e9f5b7af75 FOREIGN KEY (address_id) REFERENCES public.address(id);


--
-- PostgreSQL database dump complete
--

