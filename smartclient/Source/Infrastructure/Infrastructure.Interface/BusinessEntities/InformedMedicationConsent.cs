using System;
using System.Collections.Generic;
using System.Text;

namespace Sante.EMR.SmartClient.Infrastructure.Interface.BusinessEntities
{
    public class InformedMedicationConsent : Form
    {
        private string _clientName;

        public string ClientName
        {
            get { return _clientName; }
            set { _clientName = value; }
        }
        private string _doctorName;

        public string DoctorName
        {
            get { return _doctorName; }
            set { _doctorName = value; }
        }
        private string _description;

        public string Description
        {
            get { return _description; }
            set { _description = value; }
        }
        private string _clientSignature;

        public string ClientSignature
        {
            get { return _clientSignature; }
            set { _clientSignature = value; }
        }
        private DateTime _clientSigDate;

        public DateTime ClientSigDate
        {
            get { return _clientSigDate; }
            set { _clientSigDate = value; }
        }
        private string _guadianSignature;

        public string GuardianSignature
        {
            get { return _guadianSignature; }
            set { _guadianSignature = value; }
        }
        private DateTime _guardianSigDate;

        public DateTime GuardianSigDate
        {
            get { return _guardianSigDate; }
            set { _guardianSigDate = value; }
        }
        private string _significantOtherSign;

        public string SignificantOtherSign
        {
            get { return _significantOtherSign; }
            set { _significantOtherSign = value; }
        }
        private DateTime _SigOtherSigDate;

        public DateTime SigOtherSigDate
        {
            get { return _SigOtherSigDate; }
            set { _SigOtherSigDate = value; }
        }

        private string _physicianSignature;

        public string PhysicianSignature
        {
            get { return _physicianSignature; }
            set { _physicianSignature = value; }
        }
        private DateTime _PhysicianSigDate;

        public DateTime PhysicianSigDate
        {
            get { return _PhysicianSigDate; }
            set { _PhysicianSigDate = value; }
        }
        private string _rnSignature;

        public string RnSignature
        {
            get { return _rnSignature; }
            set { _rnSignature = value; }
        }
        private string _RNSigDate;

        public string RNSigDate
        {
            get { return _RNSigDate; }
            set { _RNSigDate = value; }
        }


    }
}
