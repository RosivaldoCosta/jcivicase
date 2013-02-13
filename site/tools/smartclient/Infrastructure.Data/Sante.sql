/***
*
*  Run with OSQL -S "(local)\SQLEXPRESS" -E -i Sante.sql
*
***/
SET LANGUAGE English
USE [master] 
GO /****** Object: Database [Sante] Script Date: 04/23/2009 22:45:17 ******/ 
CREATE DATABASE [Sante] 
GO 
EXEC dbo.sp_dbcmptlevel @dbname=N'Sante', @new_cmptlevel=90 
GO 
IF (1 = FULLTEXTSERVICEPROPERTY('IsFullTextInstalled')) begin EXEC [Sante].[dbo].[sp_fulltext_database] @action = 'disable' end 
GO
USE [SCSF_GlobalBank]

GO 
ALTER DATABASE [Sante] SET ANSI_NULL_DEFAULT OFF 
GO 
ALTER DATABASE [Sante] SET ANSI_NULLS OFF 
GO 
ALTER DATABASE [Sante] SET ANSI_PADDING OFF 
GO 
ALTER DATABASE [Sante] SET ANSI_WARNINGS OFF 
GO 
ALTER DATABASE [Sante] SET ARITHABORT OFF 
GO 
ALTER DATABASE [Sante] SET AUTO_CLOSE OFF
 GO 
ALTER DATABASE [Sante] SET AUTO_CREATE_STATISTICS ON 
GO 
ALTER DATABASE [Sante] SET AUTO_SHRINK OFF 
GO 
ALTER DATABASE [Sante] SET AUTO_UPDATE_STATISTICS ON 
GO
ALTER DATABASE [Sante] SET CURSOR_CLOSE_ON_COMMIT OFF 
GO 
ALTER DATABASE [Sante] SET CURSOR_DEFAULT GLOBAL 
GO 
ALTER DATABASE [Sante] SET CONCAT_NULL_YIELDS_NULL OFF 
GO 
ALTER DATABASE [Sante] SET NUMERIC_ROUNDABORT OFF 
GO 
ALTER DATABASE [Sante] SET QUOTED_IDENTIFIER OFF 
GO
 ALTER DATABASE [Sante] SET RECURSIVE_TRIGGERS OFF 
GO
 ALTER DATABASE [Sante] SET DISABLE_BROKER 
GO 
ALTER DATABASE [Sante] SET AUTO_UPDATE_STATISTICS_ASYNC OFF 
GO 
ALTER DATABASE [Sante] SET DATE_CORRELATION_OPTIMIZATION OFF
GO 
ALTER DATABASE [Sante] SET TRUSTWORTHY OFF 
GO 
ALTER DATABASE [Sante] SET ALLOW_SNAPSHOT_ISOLATION OFF 
GO 
ALTER DATABASE [Sante] SET PARAMETERIZATION SIMPLE 
GO 
ALTER DATABASE [Sante] SET READ_WRITE 
GO 
ALTER DATABASE [Sante] SET RECOVERY FULL 
GO 
ALTER DATABASE [Sante] SET MULTI_USER 
GO 
ALTER DATABASE [Sante] SET PAGE_VERIFY CHECKSUM 
GO
CREATE TABLE [dbo].[Patient](
	[CaseNumber] [int] NULL,
	[PatientID] [int] IDENTITY(1,1) NOT NULL,
	[PFirstName] [varchar](50) NULL,
	[PLastName] [varchar](50) NULL,
	[StreetAddress] [varchar](50) NULL,
	[City] [varchar](50) NULL,
	[Zip] [int] NULL,
	[Phone] [bigint] NULL,
	[SSN] [bigint] NULL,
	[DOB] [datetime] NULL
) ON [PRIMARY]
GO
CREATE TABLE [dbo].[AuthorizationRelease](
	[ID] [bigint] IDENTITY(1,1) NOT NULL,
	[CaseNumber] [bigint] NULL,
	[Company] [varchar](50) NULL,
	[PFirstName] [varchar](50) NULL,
	[PLastName] [varchar](50) NULL,
	[PHIName] [varchar](50) NULL,
	[PHIAddress] [varchar](50) NULL,
	[PHIPhone] [bigint] NULL,
	[SocialHistory] [varchar](50) NULL,
	[PhysicalExamination] [varchar](50) NULL,
	[XRayCTMRIReports] [varchar](50) NULL,
	[AdmissionSummary] [varchar](50) NULL,
	[LaboratoryReports] [varchar](50) NULL,
	[DischargeSummary] [varchar](50) NULL,
	[TreatmentSummary] [varchar](50) NULL,
	[PsychiatricEvaluation] [varchar](50) NULL,
	[DischargeInstructions] [varchar](50) NULL,
	[Other] [varchar](50) NULL,
	[OtherDesc] [varchar](50) NULL,
	[ExpireDate] [varchar](50) NULL,
	[Person] [varchar](50) NULL,
	[PatientID] [varchar](50) NULL,
	[DOB] [varchar](50) NULL,
	[DateSigned] [varchar](50) NULL,
	[LegalRep] [varchar](50) NULL,
	[EmployeeID] [varchar](50) NULL,
	[Depart] [varchar](15) NULL,
	[EmployeeDateSigned] [varchar](50) NULL,
	[TStamp] [datetime] NULL CONSTRAINT [DF_AuthorizationRelease_TStamp]  DEFAULT (getdate())
) ON [PRIMARY]
GO
CREATE TABLE [dbo].[AuthorizationReleaseMCT](
	[CaseNumber] [bigint] NULL,
	[Company] [varchar](50) NULL,
	[PFirstName] [varchar](50) NULL,
	[PLastName] [varchar](50) NULL,
	[PHIName] [varchar](50) NULL,
	[PHIAddress] [varchar](50) NULL,
	[PHIPhone] [bigint] NULL,
	[SocialHistory] [varchar](50) NULL,
	[PhysicalExamination] [varchar](50) NULL,
	[XRayCTMRIReports] [varchar](50) NULL,
	[AdmissionSummary] [varchar](50) NULL,
	[LaboratoryReports] [varchar](50) NULL,
	[DischargeSummary] [varchar](50) NULL,
	[TreatmentSummary] [varchar](50) NULL,
	[PsychiatricEvaluation] [varchar](50) NULL,
	[DischargeInstructions] [varchar](50) NULL,
	[Other] [varchar](50) NULL,
	[OtherDesc] [varchar](50) NULL,
	[ExpireDate] [varchar](50) NULL,
	[Person] [varchar](50) NULL,
	[PatientID] [varchar](50) NULL,
	[DOB] [varchar](50) NULL,
	[DateSigned] [varchar](50) NULL,
	[LegalRep] [varchar](50) NULL,
	[EmployeeID] [varchar](50) NULL,
	[Depart] [varchar](15) NULL,
	[EmployeeDateSigned] [varchar](50) NULL,
	[TStamp] [datetime] NULL
) ON [PRIMARY]
GO
CREATE TABLE [dbo].[AuthorizationReleaseMCT](
	[CaseNumber] [bigint] NULL,
	[Company] [varchar](50) NULL,
	[PFirstName] [varchar](50) NULL,
	[PLastName] [varchar](50) NULL,
	[PHIName] [varchar](50) NULL,
	[PHIAddress] [varchar](50) NULL,
	[PHIPhone] [bigint] NULL,
	[SocialHistory] [varchar](50) NULL,
	[PhysicalExamination] [varchar](50) NULL,
	[XRayCTMRIReports] [varchar](50) NULL,
	[AdmissionSummary] [varchar](50) NULL,
	[LaboratoryReports] [varchar](50) NULL,
	[DischargeSummary] [varchar](50) NULL,
	[TreatmentSummary] [varchar](50) NULL,
	[PsychiatricEvaluation] [varchar](50) NULL,
	[DischargeInstructions] [varchar](50) NULL,
	[Other] [varchar](50) NULL,
	[OtherDesc] [varchar](50) NULL,
	[ExpireDate] [varchar](50) NULL,
	[Person] [varchar](50) NULL,
	[PatientID] [varchar](50) NULL,
	[DOB] [varchar](50) NULL,
	[DateSigned] [varchar](50) NULL,
	[LegalRep] [varchar](50) NULL,
	[EmployeeID] [varchar](50) NULL,
	[Depart] [varchar](15) NULL,
	[EmployeeDateSigned] [varchar](50) NULL,
	[TStamp] [datetime] NULL
) ON [PRIMARY]

GO
CREATE TABLE [dbo].[Casetable2](
	[CaseNumber] [bigint] IDENTITY(1,1) NOT NULL,
	[DateOpened] [datetime] NOT NULL CONSTRAINT [DF_Casetable2_DateOpened]  DEFAULT (((1)/(1))/(1900)),
	[DateClosed] [datetime] NULL,
	[ClosedInitials] [nchar](10) NULL,
	[NextTask] [varchar](50) NULL,
	[DateDue] [datetime] NULL,
	[Comments] [varchar](255) NULL,
	[SetDate] [datetime] NULL CONSTRAINT [DF_Casetable2_SetDate]  DEFAULT (getdate()),
	[Status] [varchar](50) NULL,
	[StatusDate] [datetime] NULL CONSTRAINT [DF_Casetable2_StatusDate]  DEFAULT (getdate()),
	[openby] [varchar](20) NULL,
	[TStamp] [datetime] NULL CONSTRAINT [DF_Casetable2_TStamp]  DEFAULT (getdate()),
	[Active] [bit] NOT NULL CONSTRAINT [DF_Casetable2_Active]  DEFAULT ((1)),
	[DateCorrected] [datetime] NOT NULL CONSTRAINT [DF_Casetable2_DateCorrected]  DEFAULT (((1)/(1))/(1900))
) ON [PRIMARY]
GO
CREATE TABLE [dbo].[Employee](
	[EmployeeID] [int] IDENTITY(1,1) NOT NULL,
	[UserName] [varchar](50) NULL,
	[Password] [varchar](50) NOT NULL,
	[EFirstName] [varchar](50) NULL,
	[ELastName] [varchar](50) NULL,
	[EmployeeType] [varchar](50) NULL,
	[Department] [varchar](50) NULL,
	[DoctorType] [varchar](50) NULL,
	[tstamp] [datetime] NULL CONSTRAINT [DF_Employee_tstamp]  DEFAULT (getdate())
) ON [PRIMARY]
GO
CREATE TABLE [dbo].[Intake](
	[IntakeID] [bigint] NOT NULL CONSTRAINT [DF_Intake_IntakeID]  DEFAULT ((0)),
	[EmployeeID] [varchar](20) NULL,
	[PatientID] [varchar](50) NULL,
	[CaseNumber] [bigint] NULL,
	[Date] [datetime] NULL,
	[Time] [varchar](8) NULL,
	[Veteran] [varchar](2) NULL,
	[Resident] [varchar](1) NULL,
	[PFirstName] [varchar](50) NULL,
	[PlastName] [varchar](50) NULL,
	[Address] [varchar](50) NULL,
	[City] [varchar](50) NULL,
	[state] [varchar](2) NULL,
	[Zip] [bigint] NULL,
	[Phone] [varchar](10) NULL,
	[message] [varchar](1) NULL,
	[SSN] [varchar](12) NULL,
	[DOB] [datetime] NULL,
	[Age] [int] NULL,
	[CallerName] [varchar](50) NULL,
	[CallerAddress] [varchar](50) NULL,
	[CallerCity] [varchar](50) NULL,
	[CallerZip] [int] NULL,
	[CallerState] [varchar](2) NULL,
	[CallerPhone] [bigint] NULL,
	[Relationship] [varchar](50) NULL,
	[Financial] [varchar](50) NULL,
	[ClientsName] [varchar](50) NULL,
	[Culture] [varchar](50) NULL,
	[InsuranceCompany] [varchar](50) NULL,
	[InsuranceOther] [varchar](50) NULL,
	[InsuranceID] [varchar](50) NULL,
	[InsurancePhone] [varchar](10) NULL,
	[OtherMed] [varchar](50) NULL,
	[EmergencyName] [varchar](50) NULL,
	[EmergencyPhone] [varchar](10) NULL,
	[EmergencyRelationship] [varchar](50) NULL,
	[ReasonForContact] [varchar](1200) NULL,
	[RefferalSource] [varchar](50) NULL,
	[ReferralOther] [varchar](50) NULL,
	[Officer] [varchar](50) NULL,
	[District] [varchar](50) NULL,
	[ProviderContact] [varchar](50) NULL,
	[Provider] [varchar](50) NULL,
	[ProviderPhone] [varchar](50) NULL,
	[PCP] [varchar](50) NULL,
	[PCPPhone] [varchar](10) NULL,
	[ThreateningViolence] [varchar](12) NULL,
	[HistoryOfViolence] [varchar](12) NULL,
	[SuicideIdeation] [varchar](12) NULL,
	[SuicideThreat] [varchar](12) NULL,
	[SuicideAttempt] [varchar](12) NULL,
	[Intoxication] [varchar](12) NULL,
	[IntoxicationDesc] [varchar](255) NULL,
	[DrugUse] [varchar](12) NULL,
	[ChildrenInHouse] [varchar](12) NULL,
	[ChildrenWho] [varchar](50) NULL,
	[ChildrenRelationship] [varchar](50) NULL,
	[AdultInHouse] [varchar](12) NULL,
	[AdultWho] [varchar](50) NULL,
	[AdultRelationship] [varchar](50) NULL,
	[AbuseThreatened] [varchar](12) NULL,
	[AbuseOccuring] [varchar](12) NULL,
	[WeaponPresent] [varchar](12) NULL,
	[WeaponUsed] [varchar](12) NULL,
	[GunType] [varchar](255) NULL,
	[MedicalIssues] [varchar](12) NULL,
	[MedicalDesc] [varchar](255) NULL,
	[DangerToSelf] [varchar](12) NULL,
	[DangerToOthers] [varchar](12) NULL,
	[RapidDeterioration] [varchar](12) NULL,
	[NeedsMental] [varchar](2) NULL,
	[SubstanceAbuseP] [varchar](2) NULL,
	[SituationalCrisis] [varchar](2) NULL,
	[CISM] [varchar](2) NULL,
	[Eviction] [varchar](2) NULL,
	[InpatientHospital] [varchar](2) NULL,
	[Incarceration] [varchar](2) NULL,
	[RiskHospitalization] [varchar](2) NULL,
	[RFnone] [varchar](2) NULL,
	[InformationOnly] [varchar](2) NULL,
	[CommunityEd] [varchar](2) NULL,
	[CrisisPlanOnly] [varchar](2) NULL,
	[Called911] [varchar](2) NULL,
	[MCTDispatch] [varchar](2) NULL,
	[NotifyAPS] [varchar](2) NULL,
	[NonMH] [varchar](2) NULL,
	[MH] [varchar](2) NULL,
	[CallerDisconnects] [varchar](2) NULL,
	[Frequent] [varchar](2) NULL,
	[CallerRefuse] [varchar](2) NULL,
	[PhoneBogus] [varchar](2) NULL,
	[Release] [varchar](2) NULL,
	[Limitations] [varchar](50) NULL,
	[AuditedBy] [varchar](50) NULL,
	[Drugs] [varchar](255) NULL,
	[Depart] [varchar](15) NULL,
	[OperatorNum] [varchar](50) NULL,
	[OperatorTime] [varchar](50) NULL,
	[Comments] [varchar](255) NULL,
	[MHNeeded] [varchar](10) NULL,
	[CrisisNeeded] [varchar](10) NULL,
	[CrisisPlan] [varchar](255) NULL,
	[Record911] [varchar](255) NULL,
	[FollowUp] [varchar](255) NULL,
	[CISMFlag] [varchar](50) NULL CONSTRAINT [DF_Intake_CISMFlag]  DEFAULT ('N'),
	[Referral1] [varchar](50) NULL,
	[Referral2] [varchar](50) NULL,
	[Referral3] [varchar](50) NULL,
	[Referral4] [varchar](50) NULL,
	[Referral5] [varchar](50) NULL,
	[Referral6] [varchar](50) NULL,
	[Referral7] [varchar](50) NULL,
	[Referral8] [varchar](50) NULL,
	[Referral9] [varchar](50) NULL,
	[Referral10] [varchar](50) NULL,
	[Linkage] [varchar](50) NULL,
	[CaseRelated] [varchar](50) NULL,
	[tstamp] [datetime] NULL CONSTRAINT [DF_Intake_tstamp]  DEFAULT (getdate()),
	[cstamp] [datetime] NULL,
	[Active] [bit] NULL,
	[ID] [bigint] IDENTITY(1,1) NOT NULL
) ON [PRIMARY]
GO
CREATE TABLE [dbo].[NextTask](
	[NextTaskID] [bigint] IDENTITY(1,1) NOT NULL,
	[CaseNumber] [int] NULL,
	[NextTask] [varchar](50) NULL,
	[SetDate] [datetime] NULL CONSTRAINT [DF_NextTask_SetDate]  DEFAULT (getdate()),
	[DateDue] [datetime] NULL,
	[Initial] [varchar](20) NULL,
	[DateCompleted] [datetime] NULL,
	[AppointmentStatus] [varchar](50) NULL,
	[Comments] [varchar](255) NULL,
	[Completed] [bit] NOT NULL CONSTRAINT [DF_NextTask_Completed]  DEFAULT ((0))
) ON [PRIMARY]
