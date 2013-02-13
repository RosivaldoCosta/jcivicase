using System;
using System.Collections.Generic;
using System.Text;

namespace Sante.EMR.SmartClient.Infrastructure.Interface.BusinessEntities
{
    [Serializable]
    public class Patient
    {
        private int _caseNumber;
        private int _patientId;
        private string _firstName;
        private string _lastName;
        private string _streetAddress;
        private string _city;
        //private string _state;
        private long _zipcode;
        private string _phone;
        private string _ssn;
        private DateTime _dob;

        public DateTime DOB
        {
            get
            {
                return _dob;
            }

            set
            {
            	_dob = value;
            }
        }

        public string SSN
        {
            get
            {
                return _ssn;
            }

            set
            {
            	_ssn = value;
            }
        }

        public string Phone
        {
            get
            {
                return _phone;
            }

            set
            {
            	_phone = value;
            }
        }

        public long Zip
        {
            get
            {
                return _zipcode;
            }

            set
            {
            	_zipcode = value;
            }
        }

        

        public string City
        {
            get
            {
                return _city;
            }

            set
            {
            	_city = value;
            }
        }

        public string StreetAddress
        {
            get
            {
                return _streetAddress;
            }

            set
            {
            	_streetAddress = value;
            }
        }

        public string PLastName
        {
            get
            {
                return _lastName;
            }

            set
            {
            	_lastName = value;
            }
        }

        public string PFirstName
        {
            get
            {
                return _firstName;
            }

            set
            {
            	_lastName = value;
            }
        }

        public int PatientID
        {
            get
            {
                return _patientId;
            }

            set
            {
            	_patientId = value;
            }
        }


        public int CaseNumber
        {
            get
            {
                return _caseNumber;
            }

            set
            {
            	_caseNumber = value;
            }
        }

        public void Fill(Intake _form)
        {
            this._caseNumber = _form.CaseNumber;
            this._city = _form.City;
            this._dob = _form.Dob;
            this._firstName = _form.PFirstName;
            this._lastName = _form.PlastName;
            this._phone = _form.Phone;
            //this._state = _form.state;
            this._streetAddress = _form.Address;
            this._zipcode = _form.Zip;
            //throw new Exception("The method or operation is not implemented.");
        }
    }
}
